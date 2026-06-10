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
                <form @submit.prevent="saveProfile" novalidate>
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
                            <input type="email" x-model="formData.email"
                                @input="errors.email = false"
                                :class="errors.email ? 'border-red-400 focus:border-red-400 focus:ring-red-500/10' : ''"
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                            <div x-show="errors.email" class="mt-1.5 rounded-md bg-red-50 px-3 py-2 dark:bg-red-900/20">
                                <p class="text-xs text-red-600 dark:text-red-400">Please enter a valid email address</p>
                            </div>
                        </div>

                        {{-- Password Section --}}
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-800">
                            <h5 class="mb-4 text-base font-medium text-gray-800 dark:text-white/90">
                                Change Password
                            </h5>

                            <div class="space-y-5">
                                {{-- New Password --}}
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        New Password
                                    </label>
                                    <div class="relative">
                                        <input :type="showPassword ? 'text' : 'password'" x-model="formData.password"
                                            placeholder="Enter new password"
                                            @input="errors.password = false"
                                            :class="errors.password ? 'border-red-400 focus:border-red-400 focus:ring-red-500/10' : ''"
                                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-11 pl-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                        <span @click="showPassword = !showPassword"
                                            class="absolute top-1/2 right-4 z-30 -translate-y-1/2 cursor-pointer text-gray-500 dark:text-gray-400">
                                            <svg x-show="!showPassword" class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.0002 13.8619C7.23361 13.8619 4.86803 12.1372 3.92328 9.70241C4.86804 7.26761 7.23361 5.54297 10.0002 5.54297C12.7667 5.54297 15.1323 7.26762 16.0771 9.70243C15.1323 12.1372 12.7667 13.8619 10.0002 13.8619ZM10.0002 4.04297C6.48191 4.04297 3.49489 6.30917 2.4155 9.4593C2.3615 9.61687 2.3615 9.78794 2.41549 9.94552C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C13.5184 15.3619 16.5055 13.0957 17.5849 9.94555C17.6389 9.78797 17.6389 9.6169 17.5849 9.45932C16.5055 6.30919 13.5184 4.04297 10.0002 4.04297ZM9.99151 7.84413C8.96527 7.84413 8.13333 8.67606 8.13333 9.70231C8.13333 10.7286 8.96527 11.5605 9.99151 11.5605H10.0064C11.0326 11.5605 11.8646 10.7286 11.8646 9.70231C11.8646 8.67606 11.0326 7.84413 10.0064 7.84413H9.99151Z" fill="#98A2B3" />
                                            </svg>
                                            <svg x-show="showPassword" class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.63803 3.57709C4.34513 3.2842 3.87026 3.2842 3.57737 3.57709C3.28447 3.86999 3.28447 4.34486 3.57737 4.63775L4.85323 5.91362C3.74609 6.84199 2.89363 8.06395 2.4155 9.45936C2.3615 9.61694 2.3615 9.78801 2.41549 9.94558C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C11.255 15.3619 12.4422 15.0737 13.4994 14.5598L15.3625 16.4229C15.6554 16.7158 16.1302 16.7158 16.4231 16.4229C16.716 16.13 16.716 15.6551 16.4231 15.3622L4.63803 3.57709ZM12.3608 13.4212L10.4475 11.5079C10.3061 11.5423 10.1584 11.5606 10.0064 11.5606H9.99151C8.96527 11.5606 8.13333 10.7286 8.13333 9.70237C8.13333 9.5461 8.15262 9.39434 8.18895 9.24933L5.91885 6.97923C5.03505 7.69015 4.34057 8.62704 3.92328 9.70247C4.86803 12.1373 7.23361 13.8619 10.0002 13.8619C10.8326 13.8619 11.6287 13.7058 12.3608 13.4212ZM16.0771 9.70249C15.7843 10.4569 15.3552 11.1432 14.8199 11.7311L15.8813 12.7925C16.6329 11.9813 17.2187 11.0143 17.5849 9.94561C17.6389 9.78803 17.6389 9.61696 17.5849 9.45938C16.5055 6.30925 13.5184 4.04303 10.0002 4.04303C9.13525 4.04303 8.30244 4.17999 7.52218 4.43338L8.75139 5.66259C9.1556 5.58413 9.57311 5.54303 10.0002 5.54303C12.7667 5.54303 15.1323 7.26768 16.0771 9.70249Z" fill="#98A2B3" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div x-show="errors.password" class="mt-1.5 rounded-md bg-red-50 px-3 py-2 dark:bg-red-900/20">
                                        <p class="text-xs text-red-600 dark:text-red-400" x-text="errors.password"></p>
                                    </div>
                                    <ul class="mt-2 space-y-1">
                                        <li class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                            <svg class="shrink-0" width="12" height="12" viewBox="0 0 12 12" fill="none"><circle cx="6" cy="6" r="2" fill="currentColor"/></svg>
                                            Minimum 8 characters
                                        </li>
                                        <li class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                            <svg class="shrink-0" width="12" height="12" viewBox="0 0 12 12" fill="none"><circle cx="6" cy="6" r="2" fill="currentColor"/></svg>
                                            At least one number (0–9)
                                        </li>
                                        <li class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                            <svg class="shrink-0" width="12" height="12" viewBox="0 0 12 12" fill="none"><circle cx="6" cy="6" r="2" fill="currentColor"/></svg>
                                            At least one symbol (e.g. !@#$%)
                                        </li>
                                        <li class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                            <svg class="shrink-0" width="12" height="12" viewBox="0 0 12 12" fill="none"><circle cx="6" cy="6" r="2" fill="currentColor"/></svg>
                                            At least one uppercase and one lowercase letter
                                        </li>
                                    </ul>
                                </div>

                                {{-- Confirm Password --}}
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Confirm New Password
                                    </label>
                                    <div class="relative">
                                        <input :type="showConfirmPassword ? 'text' : 'password'" x-model="formData.password_confirmation"
                                            placeholder="Confirm your new password"
                                            @input="errors.confirmPassword = false"
                                            :class="errors.confirmPassword ? 'border-red-400 focus:border-red-400 focus:ring-red-500/10' : ''"
                                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-11 pl-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                                        <span @click="showConfirmPassword = !showConfirmPassword"
                                            class="absolute top-1/2 right-4 z-30 -translate-y-1/2 cursor-pointer text-gray-500 dark:text-gray-400">
                                            <svg x-show="!showConfirmPassword" class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.0002 13.8619C7.23361 13.8619 4.86803 12.1372 3.92328 9.70241C4.86804 7.26761 7.23361 5.54297 10.0002 5.54297C12.7667 5.54297 15.1323 7.26762 16.0771 9.70243C15.1323 12.1372 12.7667 13.8619 10.0002 13.8619ZM10.0002 4.04297C6.48191 4.04297 3.49489 6.30917 2.4155 9.4593C2.3615 9.61687 2.3615 9.78794 2.41549 9.94552C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C13.5184 15.3619 16.5055 13.0957 17.5849 9.94555C17.6389 9.78797 17.6389 9.6169 17.5849 9.45932C16.5055 6.30919 13.5184 4.04297 10.0002 4.04297ZM9.99151 7.84413C8.96527 7.84413 8.13333 8.67606 8.13333 9.70231C8.13333 10.7286 8.96527 11.5605 9.99151 11.5605H10.0064C11.0326 11.5605 11.8646 10.7286 11.8646 9.70231C11.8646 8.67606 11.0326 7.84413 10.0064 7.84413H9.99151Z" fill="#98A2B3" />
                                            </svg>
                                            <svg x-show="showConfirmPassword" class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.63803 3.57709C4.34513 3.2842 3.87026 3.2842 3.57737 3.57709C3.28447 3.86999 3.28447 4.34486 3.57737 4.63775L4.85323 5.91362C3.74609 6.84199 2.89363 8.06395 2.4155 9.45936C2.3615 9.61694 2.3615 9.78801 2.41549 9.94558C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C11.255 15.3619 12.4422 15.0737 13.4994 14.5598L15.3625 16.4229C15.6554 16.7158 16.1302 16.7158 16.4231 16.4229C16.716 16.13 16.716 15.6551 16.4231 15.3622L4.63803 3.57709ZM12.3608 13.4212L10.4475 11.5079C10.3061 11.5423 10.1584 11.5606 10.0064 11.5606H9.99151C8.96527 11.5606 8.13333 10.7286 8.13333 9.70237C8.13333 9.5461 8.15262 9.39434 8.18895 9.24933L5.91885 6.97923C5.03505 7.69015 4.34057 8.62704 3.92328 9.70247C4.86803 12.1373 7.23361 13.8619 10.0002 13.8619C10.8326 13.8619 11.6287 13.7058 12.3608 13.4212ZM16.0771 9.70249C15.7843 10.4569 15.3552 11.1432 14.8199 11.7311L15.8813 12.7925C16.6329 11.9813 17.2187 11.0143 17.5849 9.94561C17.6389 9.78803 17.6389 9.61696 17.5849 9.45938C16.5055 6.30925 13.5184 4.04303 10.0002 4.04303C9.13525 4.04303 8.30244 4.17999 7.52218 4.43338L8.75139 5.66259C9.1556 5.58413 9.57311 5.54303 10.0002 5.54303C12.7667 5.54303 15.1323 7.26768 16.0771 9.70249Z" fill="#98A2B3" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div x-show="errors.confirmPassword" class="mt-1.5 rounded-md bg-red-50 px-3 py-2 dark:bg-red-900/20">
                                        <p class="text-xs text-red-600 dark:text-red-400" x-text="errors.confirmPassword"></p>
                                    </div>
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

    {{-- Error Modal --}}
    <div x-show="saveError" x-cloak class="fixed inset-0 z-[60] overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div x-show="saveError"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-sm transform rounded-2xl bg-white p-8 text-center shadow-xl dark:bg-gray-900">
                <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                    <svg class="text-red-500 dark:text-red-400" width="32" height="32" viewBox="0 0 32 32" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M16 3a13 13 0 1 0 0 26A13 13 0 0 0 16 3ZM1 16C1 7.716 7.716 1 16 1s15 6.716 15 15-6.716 15-15 15S1 24.284 1 16ZM16 9.25a.75.75 0 0 1 .75.75v7a.75.75 0 0 1-1.5 0v-7a.75.75 0 0 1 .75-.75Zm0 11a1 1 0 1 0 0 2 1 1 0 0 0 0-2Z" fill="currentColor"/>
                    </svg>
                </div>
                <h5 class="mb-2 text-xl font-semibold text-gray-800 dark:text-white/90">Invalid Password</h5>
                <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Password must meet the required guidelines.</p>
                <button @click="saveError = false"
                    class="inline-flex w-full items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    OK
                </button>
            </div>
        </div>
    </div>

    {{-- Success Modal --}}
    <div x-show="saveSuccess" x-cloak class="fixed inset-0 z-[60] overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div x-show="saveSuccess"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-sm transform rounded-2xl bg-white p-8 text-center shadow-xl dark:bg-gray-900">
                <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                    <svg class="text-green-500 dark:text-green-400" width="32" height="32" viewBox="0 0 32 32" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M16 3a13 13 0 1 0 0 26A13 13 0 0 0 16 3ZM1 16C1 7.716 7.716 1 16 1s15 6.716 15 15-6.716 15-15 15S1 24.284 1 16Zm22.56-4.44a1.5 1.5 0 0 1 0 2.12l-8 8a1.5 1.5 0 0 1-2.12 0l-4-4a1.5 1.5 0 1 1 2.12-2.12l2.94 2.94 6.94-6.94a1.5 1.5 0 0 1 2.12 0Z" fill="currentColor"/>
                    </svg>
                </div>
                <h5 class="mb-2 text-xl font-semibold text-gray-800 dark:text-white/90">Changes Saved!</h5>
                <p class="text-sm text-gray-500 dark:text-gray-400">Profile details saved successfully.</p>
            </div>
        </div>
    </div>
</div>

<script>
    function profileInfo() {
        return {
            isOpen: false,
            saving: false,
            saveSuccess: false,
            saveError: false,
            showPassword: false,
            showConfirmPassword: false,
            errors: {
                email: false,
                password: '',
                confirmPassword: '',
            },
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
                this.saveSuccess = false;
                this.saveError = false;
                this.showPassword = false;
                this.showConfirmPassword = false;
                this.errors = { email: false, password: '', confirmPassword: '' };
                this.formData.password = '';
                this.formData.password_confirmation = '';
            },

            async saveProfile() {
                if (this.saving) return;

                // Reset errors
                this.errors = { email: false, password: '', confirmPassword: '' };

                // Validate email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!this.formData.email || !emailRegex.test(this.formData.email)) {
                    this.errors.email = true;
                    return;
                }

                // Validate password fields — both are required
                if (!this.formData.password.trim()) {
                    this.errors.password = 'New password is required.';
                    return;
                }
                if (!this.formData.password_confirmation.trim()) {
                    this.errors.confirmPassword = 'Please confirm your new password.';
                    return;
                }
                if (this.formData.password !== this.formData.password_confirmation) {
                    this.errors.confirmPassword = 'Passwords do not match.';
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
                        this.isOpen = false;
                        this.saveSuccess = true;
                        setTimeout(() => window.location.reload(), 2000);
                    } else {
                        this.saveError = true;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.saveError = true;
                } finally {
                    this.saving = false;
                }
            }
        }
    }
</script>
