@php
    $latestSp = $item->salesplan->first();
    $statusKey = $latestSp ? strtolower($latestSp->status) : 'cold';
    $statusConfig = [
        'cold'           => ['label' => 'Cold',           'bg' => '#ffffff', 'text' => '#6c757d', 'rowBg' => '#ffffff'],
        'tertarik'       => ['label' => 'Tertarik',       'bg' => '#F2F527', 'text' => '#000000', 'rowBg' => '#F2F527'],
        'mau_transfer'   => ['label' => 'Mau Transfer',   'bg' => '#3CDE1D', 'text' => '#000000', 'rowBg' => '#3CDE1D'],
        'sudah_transfer' => ['label' => 'Sudah Transfer', 'bg' => '#1786E6', 'text' => '#ffffff', 'rowBg' => '#1786E6'],
        'no'             => ['label' => 'No',             'bg' => '#E61717', 'text' => '#ffffff', 'rowBg' => '#E61717'],
    ];
    $currentConfig = $statusConfig[$statusKey] ?? $statusConfig['cold'];

    $userRole = strtolower(auth()->user()->role);
    $canEdit  = ($userRole === 'cs-mbc');
    $nominalVal = $latestSp ? ($latestSp->nominal ?? 0) : 0;
@endphp

<tr data-id="{{ $item->id }}" style="background-color: {{ $currentConfig['rowBg'] }}; color: {{ $currentConfig['text'] }}; transition: background-color 0.3s ease;">
    {{-- 1. No --}}
    <td class="text-center" style="vertical-align: middle;">
        {{ isset($data) && method_exists($data, 'firstItem') ? $data->firstItem() + $loop->index : $loop->iteration }}
    </td>

    {{-- 2. Nama & WA --}}
    <td class="px-2 py-2">
        <div class="d-flex flex-column gap-2" style="min-width: 160px;">
            <div contenteditable="{{ $canEdit ? 'true' : 'false' }}" class="{{ $canEdit ? 'editable' : '' }} fw-bold text-dark text-nowrap p-1 px-2 shadow-sm" data-field="nama" style="font-size:0.95rem; border:1px solid #dee2e6; border-radius:10px; background:#fff; min-height:32px; display:flex; align-items:center;">
                {{ $item->nama }}
            </div>
            <div class="d-flex align-items-center p-1 px-2 shadow-sm" style="border:1px solid #dee2e6; border-radius:10px; background:#fff; min-height:32px;">
                <i class="bi bi-phone me-1 text-primary" style="font-size:0.8rem;"></i>
                <span contenteditable="{{ $canEdit ? 'true' : 'false' }}" class="{{ $canEdit ? 'editable' : '' }} small text-secondary flex-grow-1" data-field="no_wa" style="font-size:0.85rem; outline:none; border:none;">
                    {{ $item->no_wa }}
                </span>
            </div>
        </div>
    </td>

    {{-- 3. Sumber Leads --}}
    <td>
        <select class="form-control form-control-sm select-sumber" data-id="{{ $item->id }}" style="font-size:0.85rem;" {{ $canEdit ? '' : 'disabled' }}>
            <option value="">- Pilih -</option>
            <option value="Ads"        {{ $item->leads == 'Ads'        ? 'selected' : '' }}>Ads</option>
            <option value="Sosmed"     {{ $item->leads == 'Sosmed'     ? 'selected' : '' }}>Sosmed</option>
            <option value="Zoom"       {{ $item->leads == 'Zoom'       ? 'selected' : '' }}>Zoom</option>
            <option value="Open House" {{ $item->leads == 'Open House' ? 'selected' : '' }}>Open House</option>
            <option value="Mandiri"    {{ $item->leads == 'Mandiri'    ? 'selected' : '' }}>Mandiri</option>
        </select>
    </td>

    {{-- 4. Prov/Kota --}}
    <td>
        <div class="d-flex flex-column gap-1">
            <select class="form-control form-control-sm select-provinsi mb-1" data-id="{{ $item->id }}" style="font-size:0.8rem;" {{ $canEdit ? '' : 'disabled' }}>
                <option value="">{{ $item->provinsi_nama ?: '-- Prov --' }}</option>
            </select>
            <select class="form-control form-control-sm select-kota" data-id="{{ $item->id }}" style="font-size:0.8rem;" {{ $canEdit ? '' : 'disabled' }}>
                <option value="">{{ $item->kota_nama ?: '-- Kota --' }}</option>
            </select>
        </div>
    </td>

    {{-- 5. Nama Bisnis --}}
    <td>
        <div contenteditable="{{ $canEdit ? 'true' : 'false' }}" class="{{ $canEdit ? 'editable' : '' }} fw-bold text-dark p-1 px-2 shadow-sm" data-field="nama_bisnis" style="font-size:0.9rem; border:1px solid #dee2e6; border-radius:8px; background:#fff; min-height:28px; min-width:140px;">
            {{ $item->nama_bisnis }}
        </div>
    </td>

    {{-- 6. Situasi Bisnis --}}
    <td class="text-wrap-normal">
        <div class="read-more-container" data-type="situasi">
            <div contenteditable="{{ $canEdit ? 'true' : 'false' }}" class="{{ $canEdit ? 'editable' : '' }} fw-bold" data-field="situasi_bisnis" style="outline:none; min-height: 45px; background: rgba(255,255,255,0.9); color: #000; padding: 8px; border-radius: 8px; border: 1px solid #dee2e6;">{{ $item->situasi_bisnis }}</div>
        </div>
    </td>

    {{-- 7. Kelas Sudah Diikuti --}}
    <td class="text-center" style="vertical-align: middle;">
        @php $kelasLulus = $item->salesplan->where('status', 'sudah_transfer'); @endphp
        @if($kelasLulus->isNotEmpty())
            <div class="d-flex flex-row flex-wrap gap-2 justify-content-center p-2">
                @foreach($kelasLulus as $sp)
                    @php $namaKls=$sp->kelas?->nama_kelas??'N/A';$shortKls=str_contains($namaKls,'Muslim Indonesia')?'M1T':(str_contains($namaKls,'Muda Indonesia')?'Start-Up Muda':$namaKls); @endphp
                    <span class="badge" style="background:#1e8449;color:#fff;font-size:0.65rem;border-radius:20px;padding:5px 12px;border:2px solid #fff;">✓ {{ $shortKls }}</span>
                @endforeach
            </div>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>

    {{-- 8. Kelas Belum Diikuti --}}
    <td class="text-center" style="vertical-align: middle;">
        @php
            $kelasLulusIds = $item->salesplan->where('status','sudah_transfer')->pluck('kelas_id')->filter()->toArray();
            $today = \Carbon\Carbon::now()->startOfDay();
            $kelasBelumDiikuti = $kelas->whereNotIn('id',$kelasLulusIds)->filter(function($k) use ($today) {
                if (!$k->tanggal_selesai) return true;
                try { return \Carbon\Carbon::parse($k->tanggal_selesai)->startOfDay()->greaterThan($today); } catch (\Exception $e) { return true; }
            });
        @endphp
        @if($kelasBelumDiikuti->isNotEmpty())
            <span class="text-warning small font-weight-bold">{{ $kelasBelumDiikuti->count() }} kelas</span>
        @else
            <span class="text-success small font-weight-bold">Semua ✓</span>
        @endif
    </td>

    {{-- 9. Action --}}
    <td class="text-center" style="vertical-align: middle;">
        <div class="d-flex flex-column gap-1 align-items-center">
            @php
                $spJson = $item->salesplan->map(function($sp) {
                    return [
                        'kelas' => $sp->kelas->nama_kelas ?? 'N/A',
                        'status' => $sp->status,
                        'nominal' => $sp->nominal
                    ];
                })->toJson();
                
                $kelasJson = $kelas->map(function($k) {
                    return ['id' => $k->id, 'nama' => $k->nama_kelas];
                })->toJson();
            @endphp
            <button type="button" class="btn btn-sm btn-detail-peserta text-white" 
                style="background:#25799E; border-radius:8px; width:100px;" 
                data-id="{{ $item->id }}" 
                data-nama="{{ $item->nama }}" 
                data-no-wa="{{ $item->no_wa }}" 
                data-status="{{ $statusKey }}" 
                data-nominal="{{ $nominalVal }}"
                data-can-edit="{{ $canEdit ? '1' : '0' }}"
                data-input-oleh="{{ $item->createdBy->name ?? $item->created_by ?? '-' }}"
                data-updated-at="{{ $item->updated_at ? $item->updated_at->format('d/m/Y H:i') : '-' }}"
                data-potensi="{{ $item->potensi }}"
                data-kelas-id="{{ $item->kelas_id }}"
                data-kelas='{!! $kelasJson !!}'
                data-salesplan='{!! $spJson !!}'
                @for($i=1; $i<=10; $i++)
                    data-fu{{$i}}-hasil="{{ $item->{'fu'.$i.'_hasil'} }}"
                    data-fu{{$i}}-at="{{ $item->{'fu'.$i.'_at'} ? $item->{'fu'.$i.'_at'}->format('d/m/Y H:i') : '' }}"
                    data-fu{{$i}}-tindak-lanjut="{{ $item->{'fu'.$i.'_tindak_lanjut'} }}"
                @endfor
            >
                <i class="fas fa-eye"></i> Prospek
            </button>
            <button type="button" class="btn btn-sm btn-warning" onclick="refreshProspekDataDirect('{{ $item->id }}')" style="width:100px; font-size:0.65rem;">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <form action="{{ route('admin.database.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" style="width:100px; font-size:0.65rem;">Hapus</button>
            </form>
        </div>
    </td>
</tr>
