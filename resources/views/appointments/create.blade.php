<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl text-yellow-400 leading-tight text-center">
        {{ __('Book Appointment') }}
    </h2>
</x-slot>
    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">

            <div class="bg-gray-900 bg-opacity-95 shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('appointments.store') }}" method="POST" class="space-y-4 text-yellow-400">
                    @csrf

                    <!-- Service Dropdown -->
                    <div>
                        <label class="block mb-1 text-yellow-400 font-semibold">Service</label>
                        <select name="service" required
                                class="w-full px-4 py-2 rounded bg-gray-800 text-yellow-400 border border-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                            <option value="" disabled selected>Select a Service</option>
                            <option value="Dental Cleaning">Dental Cleaning</option>
                            <option value="Oral Examination">Oral Examination</option>
                            <option value="X-Ray Imaging">X-Ray Imaging</option>
                            <option value="Tooth Extraction">Tooth Extraction</option>
                            <option value="Root Canal Treatment">Root Canal Treatment</option>
                            <option value="Braces Consultation">Braces Consultation</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1 text-yellow-400 font-semibold">Date</label>
                        <input type="date" name="date" required
                               class="w-full px-4 py-2 rounded bg-gray-800 text-yellow-400 border border-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    </div>

                    <div>
                        <label class="block mb-1 text-yellow-400 font-semibold">Time</label>
                        <input type="time" name="time" required
                               class="w-full px-4 py-2 rounded bg-gray-800 text-yellow-400 border border-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    </div>

                    <!-- Buttons Flex Container -->
                    <div class="flex justify-between items-center">
                        <a href="{{ route('appointments.index') }}"
                           class="text-yellow-400 hover:text-yellow-500 font-semibold transition">
                            ← Back to Appointments
                        </a>

                        <button type="submit"
                                class="bg-yellow-400 text-black px-4 py-2 rounded-lg font-semibold hover:bg-yellow-500 transition">
                            Book Now
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    @include('partials.footer')
    
</x-app-layout>
