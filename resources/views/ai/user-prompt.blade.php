Given a title and HTML of a page, create the following SEO fields: title, description, tags (comma separated).
{{-- The next line should be kept, because the action requires JSON data. --}}
Please only answer in JSON format.

title: "{{ $title }}"
html: "{!! $html !!}"
