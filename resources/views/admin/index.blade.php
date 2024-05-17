<x-layout>
    <x-header>Posts Index</x-header>
    @auth
        <section>
            <div class="flex justify-end">
                {{-- <a href="/posts/create" --}}
                <a href="{{ route('posts.create') }}"
                   class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Create
                </a>
            </div>
        </section>
    @endauth
    {{-- <div>My name is {{ $username }} and my age is {{ age }}</div> --}}


    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    ID
                </th>
                <th scope="col" class="px-6 py-3">
                    Title
                </th>
                <th scope="col" class="px-6 py-3">
                    Created At
                </th>
                <th scope="col" class="px-6 py-3">

                </th>
            </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$post->id}}
                        </th>
                        <td class="px-6 py-4">
                            {{$post->title}}
                        </td>
                        <td class="px-6 py-4">
                            {{$post->created_at}}
                        </td>
                        <td class="px-6 py-4">
                            Edit/Delete
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            No Posts
                        </th>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

</x-layout>