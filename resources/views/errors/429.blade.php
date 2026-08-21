<x-error-layout title="429 Too Many Requests">
    <h1 class="text-8xl md:text-9xl font-extrabold text-slate-900">429</h1>

    <h2 class="mt-6 text-3xl md:text-4xl font-bold">
        Too Many Requests
    </h2>

    <p class="mt-4 max-w-2xl mx-auto text-lg text-slate-600">
        You've made too many requests in a short period. Please wait a moment and try again.
    </p>

    <div class="mt-10">
        <a href="{{ url('/') }}"
           class="inline-flex items-center rounded-xl bg-slate-900 px-6 py-3 font-medium text-white transition hover:bg-slate-800">
            Back to Home
        </a>
    </div>
</x-error-layout>