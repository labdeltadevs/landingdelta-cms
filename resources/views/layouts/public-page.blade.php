<x-layouts::public
    :metaTitle="$metaTitle ?? null"
    :metaDescription="$metaDescription ?? null"
    :jsonLd="$jsonLd ?? null">
    {{ $slot }}
</x-layouts::public>
