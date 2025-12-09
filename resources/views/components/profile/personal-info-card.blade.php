<div x-data="profileInfo()">
    <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="flex-1">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-6">
                    Personal Information
                </h4>

                <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                    {{-- Full Name --}}
                    <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Full Name</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $user->name }}</p>
                    </div>

                    {{-- Email --}}
                    <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Email Address</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $user->email }}</p>
                    </div>

                    {{-- Created Date --}}
                    <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Member Since</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ $user->created_at->format('M d, Y') }}
                        </p>
                    </div>

                    {{-- Last Updated --}}
                    <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Last Updated</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ $user->updated_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Edit Button --}}
            <button @click="openModal" class="edit-button">
                <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z"
                        fill="" />
                </svg>
                Edit
            </button>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div x-show="isOpen" x-cloak @keydown.escape.window="closeModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center p-4">
            {{-- Backdrop --}}
            <div @click="closeModal" x-show="isOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
            </div>

            {{-- Modal Panel --}}
            <div x-show="isOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" @click.stop
                class="relative w-full max-w-2xl transform overflow-hidden rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-11">

                {{-- Modal Header --}}
                <div class="mb-6">
                    <h4 class="text-2xl font-semibold text-gray-800 dark:text-white/90">
                        Edit Personal Information
                    </h4>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Update your profile details below.
                    </p>
                </div>

                {{-- Form --}}
                <form @submit.prevent="saveProfile">
                    <div class="space-y-5">
                        {{-- Name --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" x-model="formData.name" required
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" x-model="formData.email" required
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                        </div>

                        {{-- Password Section --}}
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-800">
                            <h5 class="mb-4 text-base font-medium text-gray-800 dark:text-white/90">
                                Change Password <span class="text-xs font-normal text-gray-500">(Optional)</span>
                            </h5>

                            <div class="space-y-5">
                                {{-- New Password --}}
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        New Password
                                    </label>
                                    <input type="password" x-model="formData.password"
                                        placeholder="Leave empty to keep current password"
                                        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Minimum 8 characters</p>
                                </div>

                                {{-- Confirm Password --}}
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Confirm New Password
                                    </label>
                                    <input type="password" x-model="formData.password_confirmation"
                                        placeholder="Confirm your new password"
                                        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-3 mt-6 lg:justify-end">
                        <button @click="closeModal" type="button"
                            class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                            Cancel
                        </button>
                        <button type="submit" :disabled="saving"
                            :class="saving ? 'opacity-50 cursor-not-allowed' : ''"
                            class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                            <span x-show="!saving">Save Changes</span>
                            <span x-show="saving">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function profileInfo() {
        return {
            isOpen: false,
            saving: false,
            formData: {
                name: '{{ $user->name }}',
                email: '{{ $user->email }}',
                password: '',
                password_confirmation: ''
            },

            openModal() {
                this.isOpen = true;
            },

            closeModal() {
                this.isOpen = false;
                // Reset password fields
                this.formData.password = '';
                this.formData.password_confirmation = '';
            },

            async saveProfile() {
                if (this.saving) return;

                // Validate password match if provided
                if (this.formData.password && this.formData.password !== this.formData.password_confirmation) {
                    alert('Passwords do not match!');
                    return;
                }

                this.saving = true;

                try {
                    const response = await fetch('/profile/update', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(this.formData)
                    });

                    const data = await response.json();

                    if (response.ok) {
                        // Show success message
                        alert('Profile updated successfully!');
                        // Reload page to show updated info
                        window.location.reload();
                    } else {
                        alert(data.message || 'Failed to update profile');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while saving');
                } finally {
                    this.saving = false;
                }
            }
        }
    }
</script>
