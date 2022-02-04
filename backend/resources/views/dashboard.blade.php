<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in!
                </div>
                @if(count($errors) > 0)
                <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                </div>
                @endif
                <form action="{{ route('thumbnail.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="thumbnail">
                    <input type="submit">
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
