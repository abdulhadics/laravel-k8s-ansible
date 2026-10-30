<!doctype html>
<html lang="en"><head><meta charset="utf-8"><title>Laravel Kubernetes Lab</title>
<style>body{font:18px system-ui;background:#eef2f6;color:#172b4d;padding:40px}main{max-width:760px;margin:auto;background:white;padding:32px;border-radius:16px}li{margin:18px 0}.ok{color:#137333}.error{color:#b3261e}</style></head>
<body><main><h1>Laravel Three-Tier Application</h1><p>Row 2: GitHub + Ansible + Kubernetes</p>
<ul><li>Presentation: NodePort entry point on port <strong>30080</strong></li>
<li>Application: Laravel {{ app()->version() }} / PHP {{ PHP_VERSION }}</li>
<li>Database: <strong class="{{ $connected ? 'ok' : 'error' }}">{{ $connected ? 'Connected: SELECT 1 succeeded' : 'Unavailable: check database pod and credentials' }}</strong></li></ul>
<p>Database host: {{ config('database.connections.mysql.host') }}</p>
<p>Application pod: {{ gethostname() }}</p><p><a href="/health/db">Verify database connectivity (JSON)</a></p></main></body></html>
