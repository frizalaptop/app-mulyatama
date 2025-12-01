<x-mail::message>

# 📢 Pengingat Penyewaan Billboard Akan Berakhir

Halo {{ $data->user_name }},

Ini adalah pengingat bahwa masa sewa billboard Anda akan **berakhir dalam {{ $data->sisa_hari }} hari**.

<x-mail::panel>
📍 **Lokasi Billboard:** {{ $data->lokasi }}  
📅 **Tanggal Mulai:** {{ \Carbon\Carbon::parse($data->tgl_awal)->format('d F Y') }}  
📅 **Tanggal Berakhir:** {{ \Carbon\Carbon::parse($data->tgl_akhir)->format('d F Y') }}  
</x-mail::panel>

---

## 📝 Ringkasan Sewa

<x-mail::table>
| Informasi | Detail |
|-----------|--------|
| Penyewa | {{ $data->user_name }} |
| Email Penyewa | {{ $data->user_email }} |
| Billboard | {{ $data->judul }} |
| Tanggal Mulai | {{ \Carbon\Carbon::parse($data->tgl_awal)->format('d F Y') }} |
| Tanggal Berakhir | {{ \Carbon\Carbon::parse($data->tgl_akhir)->format('d F Y') }} |
| Sisa Hari | {{ $data->sisa_hari }} hari |
</x-mail::table>

---

Jika Anda ingin memperpanjang masa sewa atau memiliki pertanyaan terkait tagihan, silakan hubungi kami.

<x-mail::button :url="'mailto:admin@yourcompany.com'">
Hubungi Admin
</x-mail::button>

Terima kasih,  
{{ config('app.name') }}

</x-mail::message>
