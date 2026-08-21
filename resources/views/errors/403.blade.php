<x-error-layout title="403 Forbidden">
    <h1 class="text-8xl md:text-9xl font-extrabold text-slate-900">403</h1>

    <h2 class="mt-6 text-3xl md:text-4xl font-bold">
        Access Forbidden
    </h2>

    <p class="mt-4 max-w-2xl mx-auto text-lg text-slate-600">
        You don't have permission to access this resource.
    </p>

    <div class="mt-10">
        <a href="{{ url('/') }}"
           class="inline-flex items-center rounded-xl bg-slate-900 px-6 py-3 font-medium text-white transition hover:bg-slate-800">
            Back to Home
        </a>
    </div>
</x-error-layout>