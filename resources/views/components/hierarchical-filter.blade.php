@props(['route', 'dusuns', 'rws', 'rts', 'showKelamin' => true])

<div class="p-4 bg-gray-50 rounded-xl mb-4">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ $showKelamin ? '4' : '3' }} gap-4">
        <!-- Dusun -->
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Dusun</label>
            <select name="dusun" onchange="this.form.submit()" 
                class="w-full bg-white border border-gray-300 rounded-xl text-sm px-3 py-2 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none pr-8 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.5rem_center] bg-no-repeat">
                <option value="">Semua Dusun</option>
                @foreach($dusuns as $d)
                    <option value="{{ $d }}" {{ request('dusun') == $d ? 'selected' : '' }}>{{ $d }}</option>
                @endforeach
            </select>
        </div>

        <!-- RW -->
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">RW</label>
            <select name="rw" onchange="this.form.submit()" {{ empty(request('dusun')) ? 'disabled' : '' }}
                class="w-full bg-white border border-gray-300 rounded-xl text-sm px-3 py-2 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none pr-8 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.5rem_center] bg-no-repeat disabled:opacity-50">
                <option value="">Semua RW</option>
                @foreach($rws as $r)
                    <option value="{{ $r }}" {{ request('rw') == $r ? 'selected' : '' }}>RW {{ $r }}</option>
                @endforeach
            </select>
        </div>

        <!-- RT -->
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">RT</label>
            <select name="rt" onchange="this.form.submit()" {{ empty(request('rw')) ? 'disabled' : '' }}
                class="w-full bg-white border border-gray-300 rounded-xl text-sm px-3 py-2 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none pr-8 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.5rem_center] bg-no-repeat disabled:opacity-50">
                <option value="">Semua RT</option>
                @foreach($rts as $t)
                    <option value="{{ $t }}" {{ request('rt') == $t ? 'selected' : '' }}>RT {{ $t }}</option>
                @endforeach
            </select>
        </div>

        @if($showKelamin)
        <!-- Jenis Kelamin -->
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Jenis Kelamin</label>
            <select name="kelamin" onchange="this.form.submit()" 
                class="w-full bg-white border border-gray-300 rounded-xl text-sm px-3 py-2 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none pr-8 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.5rem_center] bg-no-repeat">
                <option value="">Semua Kelamin</option>
                <option value="laki-laki" {{ request('kelamin') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="perempuan" {{ request('kelamin') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>
        @endif
    </div>

    @if(request()->anyFilled(['dusun', 'rw', 'rt', 'kelamin']))
        <div class="flex justify-end mt-2">
            <a href="{{ $route }}" class="text-[10px] font-bold text-red-500 hover:text-red-700 flex items-center gap-1 uppercase tracking-wider">
                <i class="mdi mdi-filter-remove"></i> Reset Filter
            </a>
        </div>
    @endif
</div>
