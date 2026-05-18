@php
    // Common Logic for Chapter/Reseller/Admin Chapter View
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

    $rawSkor = null;
    $rawKategori = 'COLD';
    if (preg_match('/Total Skor(?: Form)?:? ?(\d+)/i', $item->situasi_bisnis ?? '', $matches)) { $rawSkor = (int) $matches[1]; }
    if (preg_match('/Kategori: ?(\w+)/i', $item->situasi_bisnis ?? '', $matches)) { $rawKategori = strtoupper($matches[1]); }

    $userRole = strtolower(auth()->user()->role);
    $canEdit  = !in_array($userRole, ['marketing', 'administrator', 'operasional']);
@endphp

<tr data-id="{{ $item->id }}" style="background-color: {{ $currentConfig['rowBg'] }}; color: {{ $currentConfig['text'] }}; transition: background-color 0.3s ease;">
    {{-- 1. No --}}
    <td class="text-center" style="vertical-align: middle;">
        {{ isset($data) && method_exists($data, 'firstItem') ? $data->firstItem() + $loop->index : $loop->iteration }}
    </td>

    {{-- 2. Nama & WA --}}
    <td class="px-2 py-2">
        <div class="d-flex flex-column gap-2" style="min-width: 160px;">
            <div contenteditable="{{ $canEdit ? 'true' : 'false' }}"
                class="{{ $canEdit ? 'editable' : '' }} fw-bold {{ $item->is_no_potensi ? 'text-white' : 'text-dark' }} text-nowrap p-1 px-2 shadow-sm"
                data-field="nama"
                style="font-size:0.95rem; border:1px solid #dee2e6; border-radius:10px; background:{{ $item->is_no_potensi ? '#e74a3b' : '#fff' }}; transition:all 0.3s; min-height:32px; display:flex; align-items:center;">
                {{ $item->nama }}
            </div>
            <div class="d-flex align-items-center p-1 px-2 shadow-sm" style="border:1px solid #dee2e6; border-radius:10px; background:#fff; min-height:32px;">
                <i class="bi bi-phone me-1 text-primary" style="font-size:0.8rem;"></i>
                <span contenteditable="{{ $canEdit ? 'true' : 'false' }}" class="{{ $canEdit ? 'editable' : '' }} small text-secondary flex-grow-1" data-field="no_wa" style="font-size:0.85rem; outline:none; border:none;">
                    {{ $item->no_wa }}
                </span>
            </div>
            <div class="d-flex align-items-center gap-1 mt-1">
                @if($item->no_wa)
                    @php $waNumber = preg_replace('/^0/', '62', $item->no_wa); @endphp
                    <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="btn btn-success btn-sm rounded-circle d-flex align-items-center justify-content-center border-0 shadow-sm" style="width:28px; height:28px;">
                        <i class="bi bi-whatsapp" style="font-size:0.9rem;"></i>
                    </a>
                @endif
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
        <div class="d-flex flex-column gap-1" style="min-width: 120px;">
            @if(in_array($userRole, ['administrator', 'operasional']))
                <div class="p-1 px-2 shadow-sm border rounded bg-white text-muted" style="font-size: 0.75rem; border-color: #dee2e6 !important;">
                    {{ $item->provinsi_nama ?: '-' }}
                </div>
                <div class="p-1 px-2 shadow-sm border rounded bg-white fw-bold text-dark" style="font-size: 0.75rem; border-color: #dee2e6 !important;">
                    {{ $item->kota_nama ?: '-' }}
                </div>
            @else
                <select class="form-control form-control-sm select-provinsi mb-1" data-id="{{ $item->id }}" style="font-size:0.8rem;">
                    <option value="">{{ $item->provinsi_nama ?: '-- Prov --' }}</option>
                </select>
                <select class="form-control form-control-sm select-kota" data-id="{{ $item->id }}" style="font-size:0.8rem;">
                    <option value="">{{ $item->kota_nama ?: '-- Kota --' }}</option>
                </select>
            @endif
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

    {{-- 7. Rekap Penilaian --}}
    <td class="text-center" style="vertical-align: middle;">
        @php
            $badgeColor = '#6c757d';
            if($rawKategori === 'HOT') $badgeColor = '#28a745';
            elseif($rawKategori === 'WARM') $badgeColor = '#f6c23e';
        @endphp
        <span class="badge mb-1" style="background:{{ $badgeColor }};color:#fff;font-size:0.8rem;font-weight:700;border-radius:8px;padding:5px 12px;">{{ $rawKategori }}</span>
        @if($rawSkor) <br><span class="small font-weight-bold" style="color:#555;">{{ $rawSkor }} / 51</span> @endif
    </td>

    {{-- 8. Prospek M1T --}}
    <td class="text-center" style="vertical-align: middle;">
        <span class="badge shadow-sm" style="background:#25799E;color:#fff;font-size:0.85rem;font-weight:800;border-radius:8px;padding:7px 18px;border:2px solid #fff;">M1T</span>
    </td>

    {{-- 9. Status Potensi --}}
    <td class="text-center" style="vertical-align: middle;">
        <select class="form-control form-control-sm font-weight-bold status-direct-select" style="border-radius:10px;font-size:0.75rem;height:35px;background-color:{{ $currentConfig['bg'] }};color:{{ $currentConfig['text'] }};" onchange="updateStatusDirectTable('{{ $item->id }}', this)" {{ $userRole === 'administrator' ? 'disabled' : ($canEdit ? '' : 'disabled') }}>
            <option value="cold"           {{ $statusKey==='cold'           ?'selected':'' }}>⚪ Cold</option>
            <option value="tertarik"       {{ $statusKey==='tertarik'       ?'selected':'' }}>🟡 Tertarik</option>
            <option value="mau_transfer"   {{ $statusKey==='mau_transfer'   ?'selected':'' }}>🟢 Mau Transfer</option>
            <option value="sudah_transfer" {{ $statusKey==='sudah_transfer' ?'selected':'' }}>🔵 Sudah Transfer</option>
            <option value="no"             {{ $statusKey==='no'             ?'selected':'' }}>🔴 No</option>
        </select>
    </td>

    {{-- 10. PIC / Action --}}
    <td class="text-center" style="vertical-align: middle;">
        <div class="d-flex flex-column gap-2 align-items-center">
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
            @if($userRole !== 'administrator')
                <button type="button" class="btn btn-sm btn-detail-peserta text-white mb-1" 
                    style="background:#25799E; border-radius:8px; width:110px;" 
                    data-id="{{ $item->id }}" 
                    data-nama="{{ $item->nama }}" 
                    data-no-wa="{{ $item->no_wa }}" 
                    data-status="{{ $statusKey }}" 
                    data-nominal="{{ $latestSp ? ($latestSp->nominal ?? 0) : 0 }}"
                    data-can-edit="{{ $canEdit ? '1' : '0' }}"
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
            @endif

            @if(in_array($userRole, ['administrator', 'operasional']))
                <span class="badge badge-light border" style="font-size: 0.75rem; padding: 6px 12px; width: 110px;">{{ $item->createdBy?->name ?? $item->created_by }}</span>
            @else
                @if(!$item->is_no_potensi)
                    <button type="button" class="btn btn-sm btn-warning" onclick="markNoPotensi('{{ $item->id }}')" style="font-size: 0.7rem; width: 110px;">X Tidak Potensi</button>
                @endif
                <form action="{{ route('admin.database.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" style="font-size: 0.7rem; width: 110px;">Hapus</button>
                </form>
            @endif
        </div>
    </td>
</tr>
