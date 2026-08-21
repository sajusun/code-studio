<x-error-layout title="401 Unauthorized">
    <h1 class="text-8xl md:text-9xl font-extrabold text-slate-900">401</h1>

    <h2 class="mt-6 text-3xl md:text-4xl font-bold">
        Unauthorized
    </h2>

    <p class="mt-4 max-w-2xl mx-auto text-lg text-slate-600">
        You are not authorized to access this page. Please sign in with the appropriate account.
    </p>

    <div class="mt-10">
        <a href="{{ url('/') }}"
           class="inline-flex items-center rounded-xl bg-slate-900 px-6 py-3 font-medium text-white transition hover:bg-slate-800">
            Back to Home
        </a>
    </div>
</x-error-layout>