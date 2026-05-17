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
    $canEdit  = false; // Admin CS view is read-only as requested
@endphp

<tr data-id="{{ $item->id }}" style="background-color: {{ $currentConfig['rowBg'] }}; color: {{ $currentConfig['text'] }}; transition: background-color 0.3s ease;">
    {{-- 1. No --}}
    <td class="text-center" style="vertical-align: middle;">
        {{ isset($data) && method_exists($data, 'firstItem') ? $data->firstItem() + $loop->index : $loop->iteration }}
    </td>

    {{-- 2. Nama & WA --}}
    <td class="px-2 py-2">
        <div class="d-flex flex-column gap-2" style="min-width: 160px;">
            <div class="fw-bold text-dark text-nowrap p-1 px-2 shadow-sm" style="font-size:0.95rem; border:1px solid #dee2e6; border-radius:10px; background:#fff; min-height:32px; display:flex; align-items:center;">
                {{ $item->nama }}
            </div>
            <div class="d-flex align-items-center p-1 px-2 shadow-sm" style="border:1px solid #dee2e6; border-radius:10px; background:#fff; min-height:32px;">
                <i class="bi bi-phone me-1 text-primary" style="font-size:0.8rem;"></i>
                <span class="small text-secondary flex-grow-1" style="font-size:0.85rem;">{{ $item->no_wa }}</span>
            </div>
        </div>
    </td>

    {{-- 3. Sumber Leads --}}
    <td>
        <select class="form-control form-control-sm" style="font-size:0.85rem;" disabled>
            <option value="">{{ $item->leads ?: '-' }}</option>
        </select>
    </td>

    {{-- 4. Prov/Kota --}}
    <td>
        <div class="d-flex flex-column gap-1" style="min-width: 120px;">
            <div class="p-1 px-2 shadow-sm border rounded bg-white text-muted" style="font-size: 0.75rem; border-color: #dee2e6 !important;">
                {{ $item->provinsi_nama ?: '-' }}
            </div>
            <div class="p-1 px-2 shadow-sm border rounded bg-white fw-bold text-dark" style="font-size: 0.75rem; border-color: #dee2e6 !important;">
                {{ $item->kota_nama ?: '-' }}
            </div>
        </div>
    </td>

    {{-- 5. Nama Bisnis --}}
    <td>
        <div class="fw-bold text-dark p-1 px-2 shadow-sm" style="font-size:0.9rem; border:1px solid #dee2e6; border-radius:8px; background:#fff; min-height:28px; min-width:140px;">
            {{ $item->nama_bisnis }}
        </div>
    </td>

    {{-- 6. Kelas Sudah Diikuti --}}
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

    {{-- 7. Kelas Belum Diikuti --}}
    <td class="text-center" style="vertical-align: middle;">
        @php
            $kelasLulusIds = $item->salesplan->where('status','sudah_transfer')->pluck('kelas_id')->filter()->toArray();
            $today = \Carbon\Carbon::now()->startOfDay();
            $kelasBelumDiikuti = $kelas->whereNotIn('id', $kelasLulusIds)->filter(function($k) use ($today) {
                if (!$k->tanggal_selesai) return true;
                try { return \Carbon\Carbon::parse($k->tanggal_selesai)->startOfDay()->greaterThan($today); } catch (\Exception $e) { return true; }
            });
        @endphp
        @if($kelasBelumDiikuti->isNotEmpty())
            <div class="d-flex flex-row flex-wrap gap-2 justify-content-center" style="max-width:300px;">
                @foreach($kelasBelumDiikuti as $k)
                    @php $namaK=$k->nama_kelas??'';$shortK=str_contains($namaK,'Muslim Indonesia')?'M1T':(str_contains($namaK,'Muda Indonesia')?'Start-Up Muda':$namaK); @endphp
                    <span class="badge" style="background:#e67e22;color:#fff;font-size:0.65rem;border-radius:20px;padding:5px 12px;border:2px solid #fff;">{{ $shortK }}</span>
                @endforeach
            </div>
        @else
            <span class="text-success small font-weight-bold">Semua ✓</span>
        @endif
    </td>

    {{-- 8. Status Potensi --}}
    <td class="text-center" style="vertical-align: middle;">
        <select class="form-control form-control-sm font-weight-bold" style="border-radius:10px;font-size:0.75rem;height:35px;background-color:{{ $currentConfig['bg'] }};color:{{ $currentConfig['text'] }};" disabled>
            <option value="cold"           {{ $statusKey==='cold'           ?'selected':'' }}>⚪ Cold</option>
            <option value="tertarik"       {{ $statusKey==='tertarik'       ?'selected':'' }}>🟡 Tertarik</option>
            <option value="mau_transfer"   {{ $statusKey==='mau_transfer'   ?'selected':'' }}>🟢 Mau Transfer</option>
            <option value="sudah_transfer" {{ $statusKey==='sudah_transfer' ?'selected':'' }}>🔵 Sudah Transfer</option>
            <option value="no"             {{ $statusKey==='no'             ?'selected':'' }}>🔴 No</option>
        </select>
    </td>

    {{-- 9. PIC --}}
    <td class="text-center" style="vertical-align: middle;">
        <span class="badge badge-light border" style="font-size: 0.75rem; padding: 6px 12px;">{{ $item->createdBy?->name ?? $item->created_by }}</span>
    </td>

    {{-- 10. Action --}}
    <td class="text-center" style="vertical-align: middle;">
        @php
            $today = \Carbon\Carbon::now()->startOfDay();
            $kelasJson = $kelas->filter(function($k) use ($today) {
                if (!$k->tanggal_selesai) return true;
                try {
                    return \Carbon\Carbon::parse($k->tanggal_selesai)->startOfDay()->greaterThanOrEqualTo($today);
                } catch (\Exception $e) {
                    return true;
                }
            })->map(function($k) {
                return ['id' => $k->id, 'nama' => $k->nama_kelas];
            })->values()->toJson(JSON_HEX_APOS | JSON_HEX_QUOT);

            $spJson = $item->salesplan->map(function($sp) {
                return [
                    'kelas' => $sp->kelas->nama_kelas ?? 'N/A',
                    'status' => $sp->status,
                    'nominal' => $sp->nominal
                ];
            })->toJson(JSON_HEX_APOS | JSON_HEX_QUOT);
        @endphp
        <button type="button" class="btn btn-sm btn-detail-peserta text-white" 
            style="background:#25799E; border-radius:8px; width:100px;" 
            data-id="{{ $item->id }}" 
            data-nama="{{ $item->nama }}" 
            data-no-wa="{{ $item->no_wa }}" 
            data-status="{{ $statusKey }}" 
            data-nominal="{{ $latestSp ? ($latestSp->nominal ?? 0) : 0 }}"
            data-can-edit="0" {{-- Read-only for Admin CS view --}}
            data-input-oleh="{{ $item->createdBy->name ?? $item->created_by ?? '-' }}"
            data-updated-at="{{ $item->updated_at ? $item->updated_at->format('d/m/Y H:i') : '-' }}"
            data-potensi="{{ $item->potensi }}"
            data-kelas-id="{{ $item->kelas_id }}"
            data-kelas='{!! $kelasJson !!}'
            data-salesplan='{!! $spJson !!}'
            data-bant-budget="{{ $item->bant_budget }}"
            data-bant-authority="{{ $item->bant_authority }}"
            data-bant-time="{{ $item->bant_time }}"
            data-ikut-zoom="{{ $item->ikut_zoom }}"
            data-keterangan-spin="{{ $item->keterangan_spin }}"
            @for($i=1; $i<=10; $i++)
                data-fu{{$i}}-hasil="{{ $item->{'fu'.$i.'_hasil'} }}"
                data-fu{{$i}}-at="{{ $item->{'fu'.$i.'_at'} ? $item->{'fu'.$i.'_at'}->format('d/m/Y H:i') : '' }}"
                data-fu{{$i}}-tindak-lanjut="{{ $item->{'fu'.$i.'_tindak_lanjut'} }}"
            @endfor
        >
            <i class="fas fa-eye"></i> Prospek
        </button>
    </td>
</tr>
