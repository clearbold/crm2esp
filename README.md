### settings

Do not edit the default settings in `src/settings.php`. Reference those defaults and set your own variables in `storage/config/settings.php`.

Anything you put in the `cm` or `crm` arrays in your settings will be available in your routes to pass to your classes as `$this->cm` or `$this->crm`. For example:

```
$client = new Client($this->cm['clientId'], $this->cm['clientApiKey']);
```

### TODO

* [ ] If no CM API key is set, raise an error
