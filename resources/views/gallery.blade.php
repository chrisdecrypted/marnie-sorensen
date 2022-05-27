<x-app-layout>
    @php
        $photos = App\Models\Photo::paginate(6);
    @endphp
    <x-slot name="header">
        Gallery
    </x-slot>



    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mx-auto">
        @foreach ($photos as $photo)
        <div class="col-span-1">
            <img id="click-{{ $photo->id }}" class="aspect-w-4 aspect-h-3 h-64 mx-auto" src="{{ $photo->image }}" />
        </div>


        <script>
            document.querySelector('#click-{{ $photo->id }}').onclick = () => {
                const instance = basicLightbox.create(`
                <div class="flex justify-end items-center pb-2">                                
                    <img class="cursor-pointer h-7" id="close" src="{{ asset('images/modal-close.svg') }}" />
                </div>
        
                <div>
                    <img class="aspect-w-4 aspect-h-3 w-full max-w-6xl mx-auto" src="{{ $photo->image }}"/>
                </div>
              
        
                `, {
                    onShow: (instance) => {
                        instance.element().querySelector('#close').onclick = instance.close
                    }
                }).show()
        
                instance.show()
            }
        </script>

        @endforeach
    </div>

    <div class="mt-8">
        {{ $photos->links() }}
    </div>


    @push('lightbox')
    <!-- BasicLightBox -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/basiclightbox@5.0.4/dist/basicLightbox.min.css" integrity="sha256-r7Neol40GubQBzMKAJovEaXbl9FClnADCrIMPljlx3E=" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/basiclightbox@5.0.4/dist/basicLightbox.min.js" integrity="sha256-nMn34BfOxpKD0GwV5nZMwdS4e8SI8Ekz+G7dLeGE4XY=" crossorigin="anonymous"></script>
@endpush


</x-app-layout>
