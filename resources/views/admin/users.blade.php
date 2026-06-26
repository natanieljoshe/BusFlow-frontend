@extends('admin.layouts.admin')

@section('title', 'Users & RBAC Management')
@section('header_title', 'Users & RBAC')
@section('header_subtitle', 'Manage system users and access control')

@section('content')
<div class="flex flex-col gap-6">

    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 border border-indigo-500/10">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-500 rounded-full mix-blend-screen filter blur-3xl opacity-20"></div>
        <div class="flex items-center justify-between z-10 relative">
            <div>
                <h3 class="text-lg font-semibold text-white mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-user-shield text-indigo-400"></i> Identity & Access Management
                </h3>
                <p class="text-sm text-indigo-200/70 max-w-2xl">
                    Manage all platform users, update their credentials, and control their roles and permissions across the system.
                </p>
            </div>
            <button onclick="openUserModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-lg transition-colors flex items-center gap-2">
                <i class="fa-solid fa-user-plus"></i> Add User
            </button>
        </div>
    </div>

    <!-- User Modal -->
    <div id="user-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-700 rounded-xl w-full max-w-md p-6 shadow-2xl transform scale-95 transition-transform duration-300" id="user-modal-content">
            <h3 id="modal-title" class="text-xl font-bold text-white mb-4">Add User</h3>
            <form id="user-form" class="space-y-4">
                <input type="hidden" id="user-id">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Name</label>
                    <input type="text" id="user-name" required class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Email</label>
                    <input type="email" id="user-email" required class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Password <span class="text-[10px] text-slate-500">(Leave blank to keep current if editing)</span></label>
                    <input type="password" id="user-password" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Role</label>
                    <select id="user-role" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-sm">
                        <option value="passenger">Passenger</option>
                        <option value="driver">Driver</option>
                        <option value="conductor">Conductor</option>
                        <option value="operator">Operator</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeUserModal()" class="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition-colors">Cancel</button>
                    <button type="submit" id="save-user-btn" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-lg transition-colors flex items-center gap-2">
                        Save User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800 rounded-xl overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/80 text-[11px] uppercase tracking-wider text-slate-400 border-b border-slate-800">
                        <th class="px-6 py-4 font-medium">Name</th>
                        <th class="px-6 py-4 font-medium">Email</th>
                        <th class="px-6 py-4 font-medium">Role</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-800/50" id="users-table-body">
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i> Loading users...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const API_URL = window.API_URL || 'http://localhost:8001';
    const token = localStorage.getItem('token');
    if (!token) {
        document.getElementById('users-table-body').innerHTML = `<tr><td colspan="4" class="px-6 py-8 text-center text-red-400">Silakan login terlebih dahulu.</td></tr>`;
        return;
    }

    const headers = {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
    };

    window.loadUsers = async function() {
        try {
            const response = await fetch(`${API_URL}/api/admin/users`, { headers });
            if (response.status === 401) {
                localStorage.removeItem('token');
                document.getElementById('users-table-body').innerHTML = `<tr><td colspan="4" class="px-6 py-8 text-center text-red-400">Sesi telah berakhir. Silakan login kembali.</td></tr>`;
                return;
            }
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            
            const data = await response.json();
            const usersList = Array.isArray(data) ? data : (data.data || []);
            
            const tbody = document.getElementById('users-table-body');
            tbody.innerHTML = '';
            
            if (usersList.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-8 text-center text-slate-500">No users found.</td></tr>`;
                return;
            }

            usersList.forEach(u => {
                const name = u.name || 'Unknown';
                const email = u.email || 'No email';
                const role = (u.role || 'passenger').toLowerCase();
                const id = u.id;
                
                let roleColor = 'slate';
                if(role === 'admin') roleColor = 'red';
                else if(role === 'operator') roleColor = 'amber';
                else if(role === 'driver') roleColor = 'emerald';
                else if(role === 'conductor') roleColor = 'sky';
                
                tbody.innerHTML += `
                <tr class="hover:bg-slate-800/40 transition-colors group">
                    <td class="px-6 py-4 font-medium text-slate-200">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center border border-slate-700">
                                <i class="fa-solid fa-user text-slate-400 text-xs"></i>
                            </div>
                            ${name}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-400">${email}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-${roleColor}-500/10 text-${roleColor}-400 border border-${roleColor}-500/20">
                            ${role}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button onclick="editUser(${id}, '${name.replace(/'/g, "\\'")}', '${email.replace(/'/g, "\\'")}', '${role}')" class="px-3 py-1.5 rounded-lg border border-slate-700 text-slate-400 hover:bg-indigo-500/10 hover:text-indigo-400 hover:border-indigo-500/30 transition-colors text-xs font-medium mr-2"><i class="fa-solid fa-pen"></i></button>
                        <button onclick="deleteUser(${id})" class="px-3 py-1.5 rounded-lg border border-slate-700 text-slate-400 hover:bg-red-500/10 hover:text-red-400 hover:border-red-500/30 transition-colors text-xs font-medium"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>`;
            });

        } catch (error) {
            console.error("Error fetching users:", error);
            document.getElementById('users-table-body').innerHTML = `<tr><td colspan="4" class="px-6 py-8 text-center text-red-400">Failed to load users data.</td></tr>`;
        }
    };

    window.openUserModal = function() {
        document.getElementById('modal-title').innerText = 'Add User';
        document.getElementById('user-id').value = '';
        document.getElementById('user-name').value = '';
        document.getElementById('user-email').value = '';
        document.getElementById('user-password').value = '';
        document.getElementById('user-role').value = 'passenger';
        document.getElementById('user-password').required = true;
        const modal = document.getElementById('user-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => document.getElementById('user-modal-content').classList.remove('scale-95'), 10);
    };

    window.closeUserModal = function() {
        document.getElementById('user-modal-content').classList.add('scale-95');
        setTimeout(() => {
            const modal = document.getElementById('user-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    };

    window.editUser = function(id, name, email, role) {
        document.getElementById('modal-title').innerText = 'Edit User';
        document.getElementById('user-id').value = id;
        document.getElementById('user-name').value = name;
        document.getElementById('user-email').value = email;
        document.getElementById('user-password').value = '';
        document.getElementById('user-role').value = role;
        document.getElementById('user-password').required = false;
        const modal = document.getElementById('user-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => document.getElementById('user-modal-content').classList.remove('scale-95'), 10);
    };

    window.deleteUser = async function(id) {
        if(!confirm('Are you sure you want to delete this user?')) return;
        try {
            const response = await fetch(`${window.API_URL || 'http://localhost:8001'}/api/admin/users/${id}`, {
                method: 'DELETE',
                headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}`, 'Accept': 'application/json' }
            });
            if(response.ok) {
                loadUsers();
            } else {
                alert('Failed to delete user');
            }
        } catch(e) {
            alert('Error deleting user');
        }
    };

    document.getElementById('user-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('user-id').value;
        const name = document.getElementById('user-name').value;
        const email = document.getElementById('user-email').value;
        const password = document.getElementById('user-password').value;
        const role = document.getElementById('user-role').value;

        const method = id ? 'PUT' : 'POST';
        const url = id ? `/api/admin/users/${id}` : '/api/admin/users';
        const btn = document.getElementById('save-user-btn');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
        btn.disabled = true;

        const payload = { name: name, email: email, role: role };
        if (password) payload.password = password;

        try {
            const response = await fetch(`${window.API_URL || 'http://localhost:8001'}${url}`, {
                method: method,
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            
            if(response.ok) {
                closeUserModal();
                loadUsers();
            } else {
                const data = await response.json();
                let msg = 'Unknown error';
                if(data.errors) {
                    const firstKey = Object.keys(data.errors)[0];
                    msg = data.errors[firstKey][0];
                } else if(data.message) {
                    msg = data.message;
                }
                alert('Failed to save: ' + msg);
            }
        } catch(err) {
            alert('Error saving user');
        } finally {
            btn.innerHTML = 'Save User';
            btn.disabled = false;
        }
    });

    loadUsers();
});
</script>
@endpush
@endsection
