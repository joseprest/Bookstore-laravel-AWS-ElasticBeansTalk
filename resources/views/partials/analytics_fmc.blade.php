<script>
    var digitalData = digitalData || {};
    digitalData = {
        page: {
            applicationId: '{{ config('services.adobe_fmc.app_id') }}',
            siteName: '{{ config('services.adobe_fmc.site_name') }}',
            mediaName: '{{ config('services.adobe_fmc.media_name') }}',
            pageUrl: '{{ Request::url() }}'
        },
    };
</script>
