# Server functions

- Check to see if the current page is being served over SSL.

```php
is_https(): bool
```

- Determine if current page request type is ajax.

```php
is_ajax(): bool
```

- Return the current URL.

```php
get_current_url(): string
```

- Returns the IP address of the client.

```php
get_client_ip(?bool $header_containing_ip_address = null): string
```