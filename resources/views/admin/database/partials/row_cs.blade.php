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
    $canEdit  = false; // Administrators have read-only access in CS Database view
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
            <div contenteditable="false" class="fw-bold text-dark text-nowrap p-1 px-2 shadow-sm" style="font-size:0.95rem; border:1px solid #dee2e6; border-radius:10px; background:#fff; min-height:32px; display:flex; align-items:center;">
                {{ $item->nama }}
            </div>
            <div class="d-flex align-items-center p-1 px-2 shadow-sm" style="border:1px solid #dee2e6; border-radius:10px; background:#fff; min-height:32px;">
                <i class="bi bi-phone me-1 text-primary" style="font-size:0.8rem;"></i>
                <span contenteditable="false" class="small text-secondary flex-grow-1" style="font-size:0.85rem; outline:none; border:none;">
                    {{ $item->no_wa }}
                </span>
            </div>
        </div>
    </td>

    {{-- 3. Sumber Leads --}}
    <td>
        <select class="form-control form-control-sm select-sumber" data-id="{{ $item->id }}" style="font-size:0.85rem;" disabled>
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
            <select class="form-control form-control-sm select-provinsi mb-1" data-id="{{ $item->id }}" style="font-size:0.8rem;" disabled>
                <option value="">{{ $item->provinsi_nama ?: '-- Prov --' }}</option>
            </select>
            <select class="form-control form-control-sm select-kota" data-id="{{ $item->id }}" style="font-size:0.8rem;" disabled>
                <option value="">{{ $item->kota_nama ?: '-- Kota --' }}</option>
            </select>
        </div>
    </td>

    {{-- 5. Nama Bisnis --}}
    <td>
        <div contenteditable="false" class="fw-bold text-dark p-1 px-2 shadow-sm" style="font-size:0.9rem; border:1px solid #dee2e6; border-radius:8px; background:#fff; min-height:28px; min-width:140px;">
            {{ $item->nama_bisnis }}
        </div>
    </td>

    {{-- 6. Situasi Bisnis --}}
    <td class="text-wrap-normal">
        <div class="read-more-container" data-type="situasi">
            <div contenteditable="false" class="fw-bold" style="outline:none; min-height: 45px; background: rgba(255,255,255,0.9); color: #000; padding: 8px; border-radius: 8px; border: 1px solid #dee2e6;">{{ $item->situasi_bisnis }}</div>
        </div>
    </td>
    
    {{-- 7. Daftar Prospek (Status Badges) --}}
    <td class="text-center" style="vertical-align: middle;">
        @php
            $validSalesplans = $item->salesplan->filter(function($sp) {
                return $sp->kelas_id != null;
            });
        @endphp
        @if($validSalesplans->isNotEmpty())
            <div class="d-flex flex-column justify-content-center p-2">
                @foreach($validSalesplans as $sp)
                    @php 
                        $namaKls = $sp->kelas?->nama_kelas ?? '';
                        $shortKls = str_contains($namaKls,'Muslim Indonesia') ? 'M1T' : (str_contains($namaKls,'Muda Indonesia') ? 'Start-Up Muda' : $namaKls);
                        $statusKeySP = strtolower($sp->status);
                        $cfg = $statusConfig[$statusKeySP] ?? $statusConfig['cold'];
                    @endphp
                    <span class="badge shadow-sm mb-1" style="background:{{ $cfg['bg'] }};color:{{ $cfg['text'] }};font-size:0.75rem;border-radius:8px;padding:6px 12px;border:1px solid #ccc; text-wrap: normal; word-break: break-word;">
                        {{ $shortKls }}
                        @if($statusKeySP === 'sudah_transfer') ✓ @endif
                    </span>
                @endforeach
            </div>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>

    {{-- 8. CS PIC --}}
    <td class="text-center" style="vertical-align: middle;">
        <span class="badge badge-light border text-dark fw-bold" style="font-size: 0.75rem; padding: 6px 12px;">{{ $item->createdBy?->name ?? $item->created_by ?? '-' }}</span>
    </td>

    {{-- 9. Action --}}
    <td class="text-center" style="vertical-align: middle;">
        <div class="d-flex flex-column align-items-center">
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
                data-nominal="{{ $nominalVal }}"
                data-can-edit="0" {{-- Strict Read-Only --}}
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
        </div>
    </td>
</tr>
