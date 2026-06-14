<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class UserDashboardController extends Controller
{
    public function routes(Request $request): View
    {
        $origin = $request->string('origin')->trim()->toString();
        $destination = $request->string('destination')->trim()->toString();
        $userId = $this->currentUserId($request);

        $recommendedRoutes = $this->queryOrEmpty(function () use ($origin, $destination) {
            return DB::table('routes as r')
                ->leftJoin('stops as origin_stop', 'r.origin_stop_id', '=', 'origin_stop.id')
                ->leftJoin('stops as destination_stop', 'r.destination_stop_id', '=', 'destination_stop.id')
                ->where('r.is_active', true)
                ->when($origin !== '', function ($query) use ($origin) {
                    $query->where(function ($query) use ($origin) {
                        $query->where('origin_stop.name', 'like', "%{$origin}%")
                            ->orWhere('origin_stop.code', 'like', "%{$origin}%");
                    });
                })
                ->when($destination !== '', function ($query) use ($destination) {
                    $query->where(function ($query) use ($destination) {
                        $query->where('destination_stop.name', 'like', "%{$destination}%")
                            ->orWhere('destination_stop.code', 'like', "%{$destination}%");
                    });
                })
                ->select([
                    'r.id',
                    'r.code',
                    'r.name',
                    'r.time_start as departure_time',
                    'r.total_distance_km',
                    'origin_stop.name as origin_name',
                    'destination_stop.name as destination_name',
                ])
                ->selectRaw('(select coalesce(sum(route_stops.estimated_travel_time_min), 0) from route_stops where route_stops.route_id = r.id) as eta_minutes')
                ->orderBy('r.name')
                ->limit(6)
                ->get()
                ->map(fn ($route) => [
                    'id' => $route->id,
                    'code' => $route->code,
                    'name' => $route->name,
                    'origin_name' => $route->origin_name,
                    'destination_name' => $route->destination_name,
                    'departure_time' => $this->formatTime($route->departure_time),
                    'eta_minutes' => (int) $route->eta_minutes,
                    'fare' => null,
                    'load_percent' => null,
                ]);
        });

        $scheduleRows = $this->queryOrEmpty(function () use ($origin, $destination) {
            return DB::table('trips as t')
                ->join('routes as r', 't.route_id', '=', 'r.id')
                ->leftJoin('stops as origin_stop', 'r.origin_stop_id', '=', 'origin_stop.id')
                ->leftJoin('stops as destination_stop', 'r.destination_stop_id', '=', 'destination_stop.id')
                ->where('t.is_active', true)
                ->when($origin !== '', function ($query) use ($origin) {
                    $query->where(function ($query) use ($origin) {
                        $query->where('origin_stop.name', 'like', "%{$origin}%")
                            ->orWhere('origin_stop.code', 'like', "%{$origin}%");
                    });
                })
                ->when($destination !== '', function ($query) use ($destination) {
                    $query->where(function ($query) use ($destination) {
                        $query->where('destination_stop.name', 'like', "%{$destination}%")
                            ->orWhere('destination_stop.code', 'like', "%{$destination}%");
                    });
                })
                ->select([
                    'r.code as route_code',
                    'origin_stop.name as stop_name',
                    't.departure_time',
                    't.estimated_arrival',
                ])
                ->orderBy('t.departure_time')
                ->limit(8)
                ->get()
                ->map(fn ($trip) => [
                    'route_code' => $trip->route_code,
                    'stop_name' => $trip->stop_name,
                    'departure_time' => $this->formatTime($trip->departure_time),
                    'arrival_time' => $this->formatTime($trip->estimated_arrival),
                    'status' => 'Scheduled',
                ]);
        });

        $activeBusCount = $this->queryScalar(function () {
            return DB::table('trips')
                ->where('is_active', true)
                ->whereNotNull('bus_id')
                ->distinct('bus_id')
                ->count('bus_id');
        });

        $averageEta = $this->queryScalar(function () {
            $minutes = DB::table('route_stops')->avg('estimated_travel_time_min');

            return $minutes ? round($minutes).'m' : '-';
        }, '-');

        $savedStopCount = $userId ? $this->queryScalar(function () use ($userId) {
            return DB::table('favourite_routes')
                ->where('user_id', $userId)
                ->whereNotNull('stop_id')
                ->distinct('stop_id')
                ->count('stop_id');
        }) : '-';

        $tracking = $this->latestTracking();

        return view('user.routes', [
            'recommendedRoutes' => $recommendedRoutes,
            'scheduleRows' => $scheduleRows,
            'tracking' => $tracking,
            'activeBusCount' => $activeBusCount,
            'averageEta' => $averageEta,
            'savedStopCount' => $savedStopCount,
            'databaseMessage' => $this->databaseMessage(),
        ]);
    }

    public function payments(Request $request): View
    {
        $userId = $this->currentUserId($request);
        $walletRecord = null;
        $wallet = null;
        $walletActivities = collect();
        $booking = null;

        if ($userId) {
            $walletRecord = $this->queryScalar(fn () => DB::table('wallets')->where('user_id', $userId)->first());

            if ($walletRecord) {
                $wallet = [
                    'id' => $walletRecord->id,
                    'balance' => $walletRecord->balance,
                    'status' => 'Wallet active',
                    'is_active' => true,
                ];

                $walletActivities = $this->queryOrEmpty(function () use ($walletRecord) {
                    return DB::table('wallet_transaction_histories')
                        ->where('wallet_id', $walletRecord->id)
                        ->latest('created_at')
                        ->limit(8)
                        ->get()
                        ->map(fn ($activity) => [
                            'title' => $this->walletActivityTitle($activity->type),
                            'meta' => trim(($activity->description ?: $activity->reference_id ?: 'Wallet transaction').' - '.$this->formatDateTime($activity->created_at)),
                            'amount' => $activity->type === 'fare' ? -abs((float) $activity->amount) : (float) $activity->amount,
                        ]);
                });
            }

            $booking = $this->queryScalar(function () use ($userId) {
                return DB::table('trip_bookings')
                    ->where('user_id', $userId)
                    ->whereIn('status', ['booked', 'active'])
                    ->latest('created_at')
                    ->first();
            });
        }

        return view('user.payments', [
            'wallet' => $wallet,
            'walletActivities' => $walletActivities,
            'qrCode' => $booking?->qr_code,
            'qrExpiresIn' => $booking ? 'Booking status: '.$booking->status : null,
            'topUpUrl' => '#',
            'walletCardUrl' => '#',
            'tapInAction' => '#',
            'tapOutAction' => '#',
            'databaseMessage' => $this->userDataMessage($userId),
        ]);
    }

    public function myTrip(Request $request): View
    {
        $userId = $this->currentUserId($request);
        $currentTrip = $userId ? $this->activeTrip($userId) : null;

        return view('user.my-trip', [
            'currentTrip' => $currentTrip,
            'ratingAction' => route('user.my-trip.rating'),
            'databaseMessage' => $this->userDataMessage($userId),
        ]);
    }

    public function favourites(Request $request): View
    {
        $userId = $this->currentUserId($request);
        $tripHistory = collect();
        $favorites = collect();

        if ($userId) {
            $tripHistory = $this->queryOrEmpty(function () use ($userId) {
                return DB::table('trip_bookings as tb')
                    ->leftJoin('trips as t', 'tb.trip_id', '=', 't.id')
                    ->leftJoin('routes as r', 't.route_id', '=', 'r.id')
                    ->leftJoin('buses as b', 't.bus_id', '=', 'b.id')
                    ->leftJoin('stops as boarding_stop', 'tb.boarding_stop_id', '=', 'boarding_stop.id')
                    ->leftJoin('stops as arrive_stop', 'tb.arrive_stop_id', '=', 'arrive_stop.id')
                    ->where('tb.user_id', $userId)
                    ->latest('tb.created_at')
                    ->limit(12)
                    ->select([
                        'tb.id',
                        'tb.status',
                        'tb.fare',
                        'tb.created_at',
                        'tb.tapped_out_at',
                        'r.name as route_name',
                        'b.hull_number',
                        'b.plate_number',
                        'boarding_stop.name as boarding_name',
                        'arrive_stop.name as arrive_name',
                    ])
                    ->get()
                    ->map(fn ($booking) => [
                        'route_name' => $this->tripRouteLabel($booking->route_name, $booking->boarding_name, $booking->arrive_name),
                        'date' => $this->formatDateTime($booking->tapped_out_at ?: $booking->created_at),
                        'bus_code' => $booking->hull_number ?: $booking->plate_number,
                        'fare' => $booking->fare,
                        'status' => Str::headline($booking->status ?: '-'),
                    ]);
            });

            $favorites = $this->queryOrEmpty(function () use ($userId) {
                return DB::table('favourite_routes as fr')
                    ->leftJoin('routes as r', 'fr.route_id', '=', 'r.id')
                    ->leftJoin('stops as s', 'fr.stop_id', '=', 's.id')
                    ->where('fr.user_id', $userId)
                    ->latest('fr.created_at')
                    ->select([
                        'fr.id',
                        'fr.label',
                        'fr.description',
                        'fr.route_id',
                        'fr.stop_id',
                        'r.name as route_name',
                        'r.code as route_code',
                        's.name as stop_name',
                        's.code as stop_code',
                    ])
                    ->get()
                    ->map(fn ($favorite) => [
                        'name' => $favorite->label ?: $favorite->route_name ?: $favorite->stop_name,
                        'type' => $favorite->route_id ? 'Saved route' : 'Favorite stop',
                        'note' => $favorite->description ?: trim(($favorite->route_code ?: $favorite->stop_code) ?: '-'),
                        'url' => route('user.routes', array_filter([
                            'route_id' => $favorite->route_id,
                            'stop_id' => $favorite->stop_id,
                        ])),
                    ]);
            });
        }

        return view('user.favourites', [
            'tripHistory' => $tripHistory,
            'favorites' => $favorites,
            'historyExportUrl' => '#',
            'addFavoriteUrl' => '#',
            'databaseMessage' => $this->userDataMessage($userId),
        ]);
    }

    public function storeRating(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'trip_id' => ['required', 'integer'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'feedback' => ['nullable', 'string', 'max:1000'],
        ]);

        $userId = $this->currentUserId($request);

        if (!$userId) {
            return back()->with('error', 'User belum terdeteksi. Login sebagai passenger atau kirim user_id untuk menyimpan rating.');
        }

        try {
            $booking = DB::table('trip_bookings as tb')
                ->join('trips as t', 'tb.trip_id', '=', 't.id')
                ->where('tb.id', $validated['trip_id'])
                ->where('tb.user_id', $userId)
                ->select('tb.id', 't.driver_id')
                ->first();

            if (!$booking || !$booking->driver_id) {
                return back()->with('error', 'Data trip atau sopir tidak ditemukan.');
            }

            DB::table('driver_ratings')->updateOrInsert(
                ['trip_booking_id' => $booking->id],
                [
                    'user_id' => $userId,
                    'driver_id' => $booking->driver_id,
                    'rating' => $validated['rating'],
                    'review' => $validated['feedback'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        } catch (Throwable) {
            return back()->with('error', 'Rating belum bisa disimpan karena koneksi database belum siap.');
        }

        return back()->with('status', 'Rating sopir berhasil disimpan.');
    }

    private function currentUserId(Request $request): ?int
    {
        return Auth::id() ?: ($request->integer('user_id') ?: null);
    }

    private function latestTracking(): ?array
    {
        return $this->queryScalar(function () {
            $location = DB::table('bus_locations as bl')
                ->leftJoin('trips as t', 'bl.trip_id', '=', 't.id')
                ->leftJoin('routes as r', 't.route_id', '=', 'r.id')
                ->latest('bl.recorded_at')
                ->select([
                    'bl.latitude',
                    'bl.longitude',
                    'bl.speed',
                    'bl.recorded_at',
                    'r.name as route_name',
                ])
                ->first();

            if (!$location) {
                return null;
            }

            return [
                'status' => 'GPS Active',
                'summary' => trim(($location->route_name ?: 'Bus').' - '.$location->speed.' km/h'),
                'synced_at' => $this->formatDateTime($location->recorded_at),
                'left' => $this->coordinateToPercent($location->longitude, 112.55, 112.85),
                'top' => 100 - $this->coordinateToPercent($location->latitude, -7.4, -7.1),
            ];
        });
    }

    private function activeTrip(int $userId): ?array
    {
        return $this->queryScalar(function () use ($userId) {
            $booking = DB::table('trip_bookings as tb')
                ->join('trips as t', 'tb.trip_id', '=', 't.id')
                ->join('routes as r', 't.route_id', '=', 'r.id')
                ->leftJoin('buses as b', 't.bus_id', '=', 'b.id')
                ->leftJoin('drivers as d', 't.driver_id', '=', 'd.id')
                ->leftJoin('users as driver_user', 'd.user_id', '=', 'driver_user.id')
                ->leftJoin('stops as boarding_stop', 'tb.boarding_stop_id', '=', 'boarding_stop.id')
                ->leftJoin('stops as arrive_stop', 'tb.arrive_stop_id', '=', 'arrive_stop.id')
                ->where('tb.user_id', $userId)
                ->whereIn('tb.status', ['booked', 'active'])
                ->latest('tb.created_at')
                ->select([
                    'tb.id as booking_id',
                    'tb.tapped_in_at',
                    'tb.status',
                    't.id as trip_id',
                    't.route_id',
                    't.driver_id',
                    't.estimated_arrival',
                    'r.code as route_code',
                    'b.hull_number',
                    'b.plate_number',
                    'driver_user.name as driver_name',
                    'boarding_stop.id as boarding_stop_id',
                    'boarding_stop.name as boarding_name',
                    'arrive_stop.name as arrive_name',
                ])
                ->first();

            if (!$booking) {
                return null;
            }

            $driverRating = $booking->driver_id
                ? DB::table('driver_ratings')->where('driver_id', $booking->driver_id)->avg('rating')
                : null;

            $nextStop = $this->nextStop($booking->route_id, $booking->boarding_stop_id);

            return [
                'id' => $booking->booking_id,
                'route_code' => $booking->route_code,
                'eta_minutes' => $this->minutesUntil($booking->estimated_arrival),
                'origin_name' => $booking->boarding_name,
                'destination_name' => $booking->arrive_name,
                'boarded_at' => $this->formatDateTime($booking->tapped_in_at),
                'next_stop_name' => $nextStop['name'] ?? null,
                'next_stop_eta' => isset($nextStop['eta']) ? $nextStop['eta'].' min' : '-',
                'bus' => [
                    'code' => $booking->hull_number ?: $booking->plate_number,
                    'plate_number' => $booking->plate_number,
                ],
                'driver' => [
                    'name' => $booking->driver_name,
                    'rating' => $driverRating ? number_format($driverRating, 1) : '-',
                ],
            ];
        });
    }

    private function nextStop(?int $routeId, ?int $boardingStopId): ?array
    {
        if (!$routeId || !$boardingStopId) {
            return null;
        }

        $currentStop = DB::table('route_stops')
            ->where('route_id', $routeId)
            ->where('stop_id', $boardingStopId)
            ->first();

        if (!$currentStop) {
            return null;
        }

        $nextStop = DB::table('route_stops as rs')
            ->join('stops as s', 'rs.stop_id', '=', 's.id')
            ->where('rs.route_id', $routeId)
            ->where('rs.sequence', '>', $currentStop->sequence)
            ->orderBy('rs.sequence')
            ->select('s.name', 'rs.estimated_travel_time_min')
            ->first();

        if (!$nextStop) {
            return null;
        }

        return [
            'name' => $nextStop->name,
            'eta' => $nextStop->estimated_travel_time_min,
        ];
    }

    private function queryOrEmpty(callable $callback)
    {
        try {
            return $callback();
        } catch (Throwable) {
            return collect();
        }
    }

    private function queryScalar(callable $callback, mixed $fallback = null): mixed
    {
        try {
            return $callback();
        } catch (Throwable) {
            return $fallback;
        }
    }

    private function databaseMessage(): ?string
    {
        try {
            DB::connection()->getPdo();

            return null;
        } catch (Throwable) {
            return 'Database BusFlow belum terkoneksi di environment ini. Halaman memakai query asli, tetapi data akan kosong sampai koneksi DB aktif.';
        }
    }

    private function userDataMessage(?int $userId): ?string
    {
        return $this->databaseMessage()
            ?: ($userId ? null : 'Data user belum terdeteksi. Setelah auth passenger aktif, halaman akan memakai auth()->id(); untuk tes bisa tambahkan ?user_id=ID.');
    }

    private function walletActivityTitle(?string $type): string
    {
        return match ($type) {
            'topup' => 'Wallet Top Up',
            'fare' => 'Fare Payment',
            'refund' => 'Ticket Refund',
            default => 'Wallet Transaction',
        };
    }

    private function tripRouteLabel(?string $routeName, ?string $boardingName, ?string $arriveName): string
    {
        if ($boardingName || $arriveName) {
            return trim(($boardingName ?: 'Asal').' -> '.($arriveName ?: 'Tujuan'));
        }

        return $routeName ?: 'Nama rute belum tersedia';
    }

    private function coordinateToPercent(mixed $value, float $min, float $max): int
    {
        $number = (float) $value;

        if ($number <= $min) {
            return 8;
        }

        if ($number >= $max) {
            return 92;
        }

        return (int) round((($number - $min) / ($max - $min)) * 84 + 8);
    }

    private function formatTime(mixed $value): string
    {
        if (!$value) {
            return '-';
        }

        return Str::of((string) $value)->beforeLast(':')->toString() ?: (string) $value;
    }

    private function formatDateTime(mixed $value): string
    {
        if (!$value) {
            return '-';
        }

        return (string) $value;
    }

    private function minutesUntil(mixed $time): string
    {
        return $time ? $this->formatTime($time) : '-';
    }
}
