CCDNComponent AttachmentBundle Configuration Reference.
=======================================================

All available configuration options are listed below with their default values.

``` yml
#
# for CCDNComponent AttachmentBundle
#
ccdn_component_attachment:
    template:
        engine:               twig
    store:
        dir:                  %ccdn_component_attachment_file_store%
    seo:
        title_length:         67
    quota_per_user:
        max_files_quantity:   20
        max_filesize_per_file:  200
        max_total_quota:      1000
    manage:
        list:
            layout_template:      CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
            attachments_per_page:  20
            attachment_uploaded_datetime_format:  d-m-Y - H:i
        upload:
            layout_template:      CCDNComponentCommonBundle:Layout:layout_body_right.html.twig
            form_theme:           CCDNComponentAttachmentBundle:Form:fields.html.twig
```

- [Return back to the docs index](index.md).
