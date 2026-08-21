<x-error-layout title="501 Not Implemented">
    <h1 class="text-8xl md:text-9xl font-extrabold text-slate-900">501</h1>

    <h2 class="mt-6 text-3xl md:text-4xl font-bold">
        Not Implemented
    </h2>

    <p class="mt-4 max-w-2xl mx-auto text-lg text-slate-600">
        The requested functionality is not available or has not been implemented yet.
    </p>

    <div class="mt-10">
        <a href="{{ url('/') }}"
           class="inline-flex items-center rounded-xl bg-slate-900 px-6 py-3 font-medium text-white transition hover:bg-slate-800">
            Back to Home
        </a>
    </div>
</x-error-layout>