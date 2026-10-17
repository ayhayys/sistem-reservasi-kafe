@extends('layouts.owner')

@section('content')
<!-- Inisialisasi Alpine.js untuk Modal Edit -->
<div x-data="{ 
    showEditCategory: false, 
    showEditMenu: false, 
    catId: '', catName: '',
    menuId: '', menuName: '', menuCat: '', menuPrice: '', menuDesc: '' 
}">

    <div class="mb-8">
        <h2 class="text-3xl font-bold text-white border-l-4 border-primary pl-4">Manajemen Menu & Kategori</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Kolom Kiri: Form Tambah -->
        <div class="space-y-8">
            <!-- Form Kategori -->
            <div class="bg-cardBg border border-borderDark rounded-xl p-6 shadow-lg">
                <h3 class="text-xl font-bold text-white mb-4">Tambah Kategori</h3>
                <form action="{{ route('owner.category.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm text-gray-400 mb-2">Nama Kategori</label>
                        <input type="text" name="name" required class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-2 text-white focus:border-primary focus:outline-none">
                    </div>
                    <button type="submit" class="w-full bg-primary hover:bg-orange-600 text-white font-bold py-2 rounded-lg transition">Simpan Kategori</button>
                </form>
            </div>

            <!-- Form Menu -->
            <div class="bg-cardBg border border-borderDark rounded-xl p-6 shadow-lg">
                <h3 class="text-xl font-bold text-white mb-4">Tambah Menu</h3>
                <form action="{{ route('owner.menu.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Kategori</label>
                            <select name="category_id" required class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-2 text-white focus:border-primary focus:outline-none">
                                <option value="">Pilih Kategori...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Nama Menu</label>
                            <input type="text" name="name" required class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-2 text-white focus:border-primary focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Harga (Rp)</label>
                            <input type="number" name="price" required min="0" class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-2 text-white focus:border-primary focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Upload Gambar (Opsional)</label>
                            <input type="file" name="image" accept="image/*" class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-2 text-white focus:border-primary focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-primary file:text-white hover:file:bg-orange-600">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Deskripsi</label>
                            <textarea name="description" rows="3" class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-2 text-white focus:border-primary focus:outline-none"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-primary hover:bg-orange-600 text-white font-bold py-2 rounded-lg transition mt-4">Simpan Menu</button>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: Tabel Data -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Tabel Kategori -->
            <div class="bg-cardBg border border-borderDark rounded-xl p-6 shadow-lg overflow-x-auto">
                <h3 class="text-xl font-bold text-white mb-4">Daftar Kategori</h3>
                <table class="w-full text-left text-sm text-gray-400">
                    <thead class="text-xs uppercase bg-darkBg text-gray-300">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-lg">Kategori</th>
                            <th class="px-4 py-3">Jumlah Menu</th>
                            <th class="px-4 py-3 text-right rounded-tr-lg">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $cat)
                        <tr class="border-b border-borderDark hover:bg-darkBg transition">
                            <td class="px-4 py-3 font-bold text-white">{{ $cat->name }}</td>
                            <td class="px-4 py-3">{{ $cat->menus_count }} Menu</td>
                            <td class="px-4 py-3 text-right flex justify-end space-x-3">
                                <!-- Tombol Edit Kategori -->
                                <button @click="catId = '{{ $cat->id }}'; catName = '{{ addslashes($cat->name) }}'; showEditCategory = true" class="text-blue-400 hover:text-blue-300 font-semibold text-xs">Edit</button>
                                <!-- Tombol Hapus -->
                                <form action="{{ route('owner.category.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Yakin hapus kategori ini permanen?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-400 font-semibold text-xs">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Tabel Menu -->
            <div class="bg-cardBg border border-borderDark rounded-xl p-6 shadow-lg overflow-x-auto">
                <h3 class="text-xl font-bold text-white mb-4">Daftar Menu Aktif</h3>
                <table class="w-full text-left text-sm text-gray-400">
                    <thead class="text-xs uppercase bg-darkBg text-gray-300">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-lg">Menu</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Harga</th>
                            <th class="px-4 py-3 text-right rounded-tr-lg">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menus as $menu)
                        <tr class="border-b border-borderDark hover:bg-darkBg transition">
                            <td class="px-4 py-3 flex items-center space-x-3">
                                @if($menu->image)
                                    <img src="{{ asset($menu->image) }}" alt="img" class="w-10 h-10 rounded object-cover">
                                @endif
                                <div>
                                    <p class="font-bold text-white">{{ $menu->name }}</p>
                                    <p class="text-xs truncate max-w-[150px]">{{ $menu->description }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $menu->category->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-primary font-semibold">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end space-x-3 mt-2">
                                    <!-- Tombol Edit Menu -->
                                    <button @click="menuId = '{{ $menu->id }}'; menuName = '{{ addslashes($menu->name) }}'; menuCat = '{{ $menu->category_id }}'; menuPrice = '{{ $menu->price }}'; menuDesc = '{{ addslashes($menu->description) }}'; showEditMenu = true" class="text-blue-400 hover:text-blue-300 font-semibold text-xs">Edit</button>
                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('owner.menu.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Yakin hapus menu ini permanen?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400 font-semibold text-xs">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- MODAL EDIT KATEGORI -->
    <div x-show="showEditCategory" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70" style="display: none;">
        <div class="bg-cardBg border border-borderDark rounded-xl p-6 w-full max-w-md shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-4">Edit Kategori</h3>
            <form :action="'/owner/category/' + catId" method="POST">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm text-gray-400 mb-2">Nama Kategori</label>
                    <input type="text" name="name" x-model="catName" required class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-2 text-white focus:border-primary focus:outline-none">
                </div>
                <div class="flex space-x-4">
                    <button type="button" @click="showEditCategory = false" class="w-1/2 bg-gray-600 hover:bg-gray-500 text-white font-bold py-2 rounded-lg transition">Batal</button>
                    <button type="submit" class="w-1/2 bg-primary hover:bg-orange-600 text-white font-bold py-2 rounded-lg transition">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT MENU -->
    <div x-show="showEditMenu" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70" style="display: none;">
        <div class="bg-cardBg border border-borderDark rounded-xl p-6 w-full max-w-lg shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-4">Edit Menu</h3>
            <form :action="'/owner/menu/' + menuId" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="space-y-4 mb-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Kategori</label>
                        <select name="category_id" x-model="menuCat" required class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-2 text-white focus:border-primary focus:outline-none">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Nama Menu</label>
                        <input type="text" name="name" x-model="menuName" required class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-2 text-white focus:border-primary focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Harga (Rp)</label>
                        <input type="number" name="price" x-model="menuPrice" required min="0" class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-2 text-white focus:border-primary focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Ganti Gambar (Opsional)</label>
                        <input type="file" name="image" accept="image/*" class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-2 text-white focus:border-primary focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-primary file:text-white">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Deskripsi</label>
                        <textarea name="description" x-model="menuDesc" rows="2" class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-2 text-white focus:border-primary focus:outline-none"></textarea>
                    </div>
                </div>
                <div class="flex space-x-4">
                    <button type="button" @click="showEditMenu = false" class="w-1/2 bg-gray-600 hover:bg-gray-500 text-white font-bold py-2 rounded-lg transition">Batal</button>
                    <button type="submit" class="w-1/2 bg-primary hover:bg-orange-600 text-white font-bold py-2 rounded-lg transition">Update Menu</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection