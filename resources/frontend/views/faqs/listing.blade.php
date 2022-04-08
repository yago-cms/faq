<x-core.accordion id="faqs">
    @foreach ($faqs as $i => $faq)
        <x-core.accordion.item id="faqs" :i="$i" :label="$faq->name" :isUseLabelAsId="true">
            {!! $faq->content !!}
        </x-core.accordion.item>
    @endforeach
</x-core.accordion>
