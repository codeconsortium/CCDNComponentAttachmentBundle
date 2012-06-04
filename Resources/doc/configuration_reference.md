CCDNComponent AttachmentBundle Configuration Reference.
=======================================================

All available configuration options are listed below with their default values.

``` yml
#
# for CCDNComponent AttachmentBundle
#
ccdn_component_attachment:
    user:
        profile_route: cc_profile_show_by_id
    template:
        engine: twig
    store:
        dir: %ccdncomponent_attachmentbundle_file_store%
    quota_per_user:
        max_files_quantity: 20
        max_filesize_per_file: 400KiB
        max_total_quota: 2000KiB
#    manage:
#        list:
#            layout_template: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
#            attachment_uploaded_datetime_format: "d-m-Y - H:i"
#        upload:
#            layout_template: CCDNComponentCommonBundle:Layout:layout_body_left.html.twig
#            form_theme: CCDNComponentAttachmentBundle:Form:fields.html.twig

```

- [Return back to the docs index](index.md).
