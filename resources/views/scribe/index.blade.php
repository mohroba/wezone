<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Wezone API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
        body .content .bash-example code {
            display: none;
        }

        body .content .javascript-example code {
            display: none;
        }
    </style>

    <script>
        var tryItOutBaseUrl = "http://wezone.test";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.3.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.3.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">

    <div class="lang-selector">
        <button type="button" class="lang-button" data-language-name="bash">bash</button>
        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
    </div>

    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
        <ul id="tocify-header-introduction" class="tocify-header">
            <li class="tocify-item level-1" data-unique="introduction">
                <a href="#introduction">Introduction</a>
            </li>
        </ul>
        <ul id="tocify-header-authenticating-requests" class="tocify-header">
            <li class="tocify-item level-1" data-unique="authenticating-requests">
                <a href="#authenticating-requests">Authenticating requests</a>
            </li>
        </ul>
        <ul id="tocify-header-endpoints" class="tocify-header">
            <li class="tocify-item level-1" data-unique="endpoints">
                <a href="#endpoints">Endpoints</a>
            </li>
            <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-auth-otp-send">
                    <a href="#endpoints-POSTapi-auth-otp-send">POST api/auth/otp/send</a>
                </li>
                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-auth-otp-verify">
                    <a href="#endpoints-POSTapi-auth-otp-verify">POST api/auth/otp/verify</a>
                </li>
                <li class="tocify-item level-2" data-unique="endpoints-GETapi-auth-profile">
                    <a href="#endpoints-GETapi-auth-profile">GET api/auth/profile</a>
                </li>
                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-auth-profile">
                    <a href="#endpoints-POSTapi-auth-profile">POST api/auth/profile</a>
                </li>
                <li class="tocify-item level-2" data-unique="endpoints-GETapi-auth-user">
                    <a href="#endpoints-GETapi-auth-user">GET api/auth/user</a>
                </li>
                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-auth-user">
                    <a href="#endpoints-POSTapi-auth-user">POST api/auth/user</a>
                </li>
            </ul>
        </ul>
    </div>

    <ul class="toc-footer" id="toc-footer">
        <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
        <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
        <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: September 25, 2025</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
        <aside>
            <strong>Base URL</strong>: <code>http://wezone.test</code>
        </aside>
        <pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
        <p>This API is not authenticated.</p>

        <h1 id="endpoints">Endpoints</h1>


        <h2 id="endpoints-POSTapi-auth-otp-send">POST api/auth/otp/send</h2>

        <p>
        </p>


        <span id="example-requests-POSTapi-auth-otp-send">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://wezone.test/api/auth/otp/send" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"mobile\": \"5642559314\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://wezone.test/api/auth/otp/send"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "mobile": "5642559314"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

        <span id="example-responses-POSTapi-auth-otp-send">
</span>
        <span id="execution-results-POSTapi-auth-otp-send" hidden>
    <blockquote>Received response<span
            id="execution-response-status-POSTapi-auth-otp-send"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-auth-otp-send"
                            data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
        <span id="execution-error-POSTapi-auth-otp-send" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-auth-otp-send">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
        <form id="form-POSTapi-auth-otp-send" data-method="POST"
              data-path="api/auth/otp/send"
              data-authed="0"
              data-hasfiles="0"
              data-isarraybody="0"
              autocomplete="off"
              onsubmit="event.preventDefault(); executeTryOut('POSTapi-auth-otp-send', this);">
            <h3>
                Request&nbsp;&nbsp;&nbsp;
                <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-POSTapi-auth-otp-send"
                        onclick="tryItOut('POSTapi-auth-otp-send');">Try it out ⚡
                </button>
                <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-POSTapi-auth-otp-send"
                        onclick="cancelTryOut('POSTapi-auth-otp-send');" hidden>Cancel 🛑
                </button>&nbsp;&nbsp;
                <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-POSTapi-auth-otp-send"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                </button>
            </h3>
            <p>
                <small class="badge badge-black">POST</small>
                <b><code>api/auth/otp/send</code></b>
            </p>
            <h4 class="fancy-heading-panel"><b>Headers</b></h4>
            <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                &nbsp;
                &nbsp;
                <input type="text" style="display: none"
                       name="Content-Type" data-endpoint="POSTapi-auth-otp-send"
                       value="application/json"
                       data-component="header">
                <br>
                <p>Example: <code>application/json</code></p>
            </div>
            <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                &nbsp;
                &nbsp;
                <input type="text" style="display: none"
                       name="Accept" data-endpoint="POSTapi-auth-otp-send"
                       value="application/json"
                       data-component="header">
                <br>
                <p>Example: <code>application/json</code></p>
            </div>
            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
            <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>mobile</code></b>&nbsp;&nbsp;
                <small>string</small>&nbsp;
                &nbsp;
                <input type="text" style="display: none"
                       name="mobile" data-endpoint="POSTapi-auth-otp-send"
                       value="5642559314"
                       data-component="body">
                <br>
                <p>Must match the regex /^[0-9]{10,15}$/. Example: <code>5642559314</code></p>
            </div>
        </form>

        <h2 id="endpoints-POSTapi-auth-otp-verify">POST api/auth/otp/verify</h2>

        <p>
        </p>


        <span id="example-requests-POSTapi-auth-otp-verify">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://wezone.test/api/auth/otp/verify" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"mobile\": \"5642559314\",
    \"otp\": \"569775\",
    \"username\": \"n\",
    \"email\": \"ashly64@example.com\",
    \"first_name\": \"v\",
    \"last_name\": \"d\",
    \"birth_date\": \"2021-10-18\",
    \"national_id\": \"n\",
    \"residence_city_id\": 67,
    \"residence_province_id\": 66
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://wezone.test/api/auth/otp/verify"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "mobile": "5642559314",
    "otp": "569775",
    "username": "n",
    "email": "ashly64@example.com",
    "first_name": "v",
    "last_name": "d",
    "birth_date": "2021-10-18",
    "national_id": "n",
    "residence_city_id": 67,
    "residence_province_id": 66
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

        <span id="example-responses-POSTapi-auth-otp-verify">
</span>
        <span id="execution-results-POSTapi-auth-otp-verify" hidden>
    <blockquote>Received response<span
            id="execution-response-status-POSTapi-auth-otp-verify"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-auth-otp-verify"
                            data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
        <span id="execution-error-POSTapi-auth-otp-verify" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-auth-otp-verify">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
        <form id="form-POSTapi-auth-otp-verify" data-method="POST"
              data-path="api/auth/otp/verify"
              data-authed="0"
              data-hasfiles="0"
              data-isarraybody="0"
              autocomplete="off"
              onsubmit="event.preventDefault(); executeTryOut('POSTapi-auth-otp-verify', this);">
            <h3>
                Request&nbsp;&nbsp;&nbsp;
                <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-POSTapi-auth-otp-verify"
                        onclick="tryItOut('POSTapi-auth-otp-verify');">Try it out ⚡
                </button>
                <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-POSTapi-auth-otp-verify"
                        onclick="cancelTryOut('POSTapi-auth-otp-verify');" hidden>Cancel 🛑
                </button>&nbsp;&nbsp;
                <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-POSTapi-auth-otp-verify"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                </button>
            </h3>
            <p>
                <small class="badge badge-black">POST</small>
                <b><code>api/auth/otp/verify</code></b>
            </p>
            <h4 class="fancy-heading-panel"><b>Headers</b></h4>
            <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                &nbsp;
                &nbsp;
                <input type="text" style="display: none"
                       name="Content-Type" data-endpoint="POSTapi-auth-otp-verify"
                       value="application/json"
                       data-component="header">
                <br>
                <p>Example: <code>application/json</code></p>
            </div>
            <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                &nbsp;
                &nbsp;
                <input type="text" style="display: none"
                       name="Accept" data-endpoint="POSTapi-auth-otp-verify"
                       value="application/json"
                       data-component="header">
                <br>
                <p>Example: <code>application/json</code></p>
            </div>
            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
            <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>mobile</code></b>&nbsp;&nbsp;
                <small>string</small>&nbsp;
                &nbsp;
                <input type="text" style="display: none"
                       name="mobile" data-endpoint="POSTapi-auth-otp-verify"
                       value="5642559314"
                       data-component="body">
                <br>
                <p>Must match the regex /^[0-9]{10,15}$/. Example: <code>5642559314</code></p>
            </div>
            <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>otp</code></b>&nbsp;&nbsp;
                <small>string</small>&nbsp;
                &nbsp;
                <input type="text" style="display: none"
                       name="otp" data-endpoint="POSTapi-auth-otp-verify"
                       value="569775"
                       data-component="body">
                <br>
                <p>Must be 6 digits. Example: <code>569775</code></p>
            </div>
            <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>username</code></b>&nbsp;&nbsp;
                <small>string</small>&nbsp;
                <i>optional</i> &nbsp;
                <input type="text" style="display: none"
                       name="username" data-endpoint="POSTapi-auth-otp-verify"
                       value="n"
                       data-component="body">
                <br>
                <p>Must not be greater than 191 characters. Example: <code>n</code></p>
            </div>
            <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
                <small>string</small>&nbsp;
                <i>optional</i> &nbsp;
                <input type="text" style="display: none"
                       name="email" data-endpoint="POSTapi-auth-otp-verify"
                       value="ashly64@example.com"
                       data-component="body">
                <br>
                <p>Must be a valid email address. Must not be greater than 191 characters. Example: <code>ashly64@example.com</code>
                </p>
            </div>
            <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>first_name</code></b>&nbsp;&nbsp;
                <small>string</small>&nbsp;
                <i>optional</i> &nbsp;
                <input type="text" style="display: none"
                       name="first_name" data-endpoint="POSTapi-auth-otp-verify"
                       value="v"
                       data-component="body">
                <br>
                <p>Must not be greater than 191 characters. Example: <code>v</code></p>
            </div>
            <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>last_name</code></b>&nbsp;&nbsp;
                <small>string</small>&nbsp;
                <i>optional</i> &nbsp;
                <input type="text" style="display: none"
                       name="last_name" data-endpoint="POSTapi-auth-otp-verify"
                       value="d"
                       data-component="body">
                <br>
                <p>Must not be greater than 191 characters. Example: <code>d</code></p>
            </div>
            <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>birth_date</code></b>&nbsp;&nbsp;
                <small>string</small>&nbsp;
                <i>optional</i> &nbsp;
                <input type="text" style="display: none"
                       name="birth_date" data-endpoint="POSTapi-auth-otp-verify"
                       value="2021-10-18"
                       data-component="body">
                <br>
                <p>Must be a valid date. Must be a date before or equal to <code>today</code>. Example:
                    <code>2021-10-18</code></p>
            </div>
            <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>national_id</code></b>&nbsp;&nbsp;
                <small>string</small>&nbsp;
                <i>optional</i> &nbsp;
                <input type="text" style="display: none"
                       name="national_id" data-endpoint="POSTapi-auth-otp-verify"
                       value="n"
                       data-component="body">
                <br>
                <p>Must not be greater than 191 characters. Example: <code>n</code></p>
            </div>
            <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>residence_city_id</code></b>&nbsp;&nbsp;
                <small>integer</small>&nbsp;
                <i>optional</i> &nbsp;
                <input type="number" style="display: none"
                       step="any" name="residence_city_id" data-endpoint="POSTapi-auth-otp-verify"
                       value="67"
                       data-component="body">
                <br>
                <p>Must be at least 1. Example: <code>67</code></p>
            </div>
            <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>residence_province_id</code></b>&nbsp;&nbsp;
                <small>integer</small>&nbsp;
                <i>optional</i> &nbsp;
                <input type="number" style="display: none"
                       step="any" name="residence_province_id" data-endpoint="POSTapi-auth-otp-verify"
                       value="66"
                       data-component="body">
                <br>
                <p>Must be at least 1. Example: <code>66</code></p>
            </div>
        </form>

        <h2 id="endpoints-GETapi-auth-profile">GET api/auth/profile</h2>

        <p>
        </p>


        <span id="example-requests-GETapi-auth-profile">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://wezone.test/api/auth/profile" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://wezone.test/api/auth/profile"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

        <span id="example-responses-GETapi-auth-profile">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
        <span id="execution-results-GETapi-auth-profile" hidden>
    <blockquote>Received response<span
            id="execution-response-status-GETapi-auth-profile"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-auth-profile"
                            data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
        <span id="execution-error-GETapi-auth-profile" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-auth-profile">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
        <form id="form-GETapi-auth-profile" data-method="GET"
              data-path="api/auth/profile"
              data-authed="0"
              data-hasfiles="0"
              data-isarraybody="0"
              autocomplete="off"
              onsubmit="event.preventDefault(); executeTryOut('GETapi-auth-profile', this);">
            <h3>
                Request&nbsp;&nbsp;&nbsp;
                <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-auth-profile"
                        onclick="tryItOut('GETapi-auth-profile');">Try it out ⚡
                </button>
                <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-auth-profile"
                        onclick="cancelTryOut('GETapi-auth-profile');" hidden>Cancel 🛑
                </button>&nbsp;&nbsp;
                <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-auth-profile"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                </button>
            </h3>
            <p>
                <small class="badge badge-green">GET</small>
                <b><code>api/auth/profile</code></b>
            </p>
            <h4 class="fancy-heading-panel"><b>Headers</b></h4>
            <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                &nbsp;
                &nbsp;
                <input type="text" style="display: none"
                       name="Content-Type" data-endpoint="GETapi-auth-profile"
                       value="application/json"
                       data-component="header">
                <br>
                <p>Example: <code>application/json</code></p>
            </div>
            <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                &nbsp;
                &nbsp;
                <input type="text" style="display: none"
                       name="Accept" data-endpoint="GETapi-auth-profile"
                       value="application/json"
                       data-component="header">
                <br>
                <p>Example: <code>application/json</code></p>
            </div>
        </form>

        <h2 id="endpoints-POSTapi-auth-profile">POST api/auth/profile</h2>

        <p>
        </p>


        <span id="example-requests-POSTapi-auth-profile">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://wezone.test/api/auth/profile" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"first_name\": \"b\",
    \"last_name\": \"n\",
    \"birth_date\": \"2021-10-18\",
    \"national_id\": \"n\",
    \"residence_city_id\": 67,
    \"residence_province_id\": 66
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://wezone.test/api/auth/profile"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "first_name": "b",
    "last_name": "n",
    "birth_date": "2021-10-18",
    "national_id": "n",
    "residence_city_id": 67,
    "residence_province_id": 66
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

        <span id="example-responses-POSTapi-auth-profile">
</span>
        <span id="execution-results-POSTapi-auth-profile" hidden>
    <blockquote>Received response<span
            id="execution-response-status-POSTapi-auth-profile"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-auth-profile"
                            data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
        <span id="execution-error-POSTapi-auth-profile" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-auth-profile">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
        <form id="form-POSTapi-auth-profile" data-method="POST"
              data-path="api/auth/profile"
              data-authed="0"
              data-hasfiles="0"
              data-isarraybody="0"
              autocomplete="off"
              onsubmit="event.preventDefault(); executeTryOut('POSTapi-auth-profile', this);">
            <h3>
                Request&nbsp;&nbsp;&nbsp;
                <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-POSTapi-auth-profile"
                        onclick="tryItOut('POSTapi-auth-profile');">Try it out ⚡
                </button>
                <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-POSTapi-auth-profile"
                        onclick="cancelTryOut('POSTapi-auth-profile');" hidden>Cancel 🛑
                </button>&nbsp;&nbsp;
                <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-POSTapi-auth-profile"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                </button>
            </h3>
            <p>
                <small class="badge badge-black">POST</small>
                <b><code>api/auth/profile</code></b>
            </p>
            <h4 class="fancy-heading-panel"><b>Headers</b></h4>
            <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                &nbsp;
                &nbsp;
                <input type="text" style="display: none"
                       name="Content-Type" data-endpoint="POSTapi-auth-profile"
                       value="application/json"
                       data-component="header">
                <br>
                <p>Example: <code>application/json</code></p>
            </div>
            <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                &nbsp;
                &nbsp;
                <input type="text" style="display: none"
                       name="Accept" data-endpoint="POSTapi-auth-profile"
                       value="application/json"
                       data-component="header">
                <br>
                <p>Example: <code>application/json</code></p>
            </div>
            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
            <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>first_name</code></b>&nbsp;&nbsp;
                <small>string</small>&nbsp;
                <i>optional</i> &nbsp;
                <input type="text" style="display: none"
                       name="first_name" data-endpoint="POSTapi-auth-profile"
                       value="b"
                       data-component="body">
                <br>
                <p>Must not be greater than 191 characters. Example: <code>b</code></p>
            </div>
            <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>last_name</code></b>&nbsp;&nbsp;
                <small>string</small>&nbsp;
                <i>optional</i> &nbsp;
                <input type="text" style="display: none"
                       name="last_name" data-endpoint="POSTapi-auth-profile"
                       value="n"
                       data-component="body">
                <br>
                <p>Must not be greater than 191 characters. Example: <code>n</code></p>
            </div>
            <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>birth_date</code></b>&nbsp;&nbsp;
                <small>string</small>&nbsp;
                <i>optional</i> &nbsp;
                <input type="text" style="display: none"
                       name="birth_date" data-endpoint="POSTapi-auth-profile"
                       value="2021-10-18"
                       data-component="body">
                <br>
                <p>Must be a valid date. Must be a date before or equal to <code>today</code>. Example:
                    <code>2021-10-18</code></p>
            </div>
            <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>national_id</code></b>&nbsp;&nbsp;
                <small>string</small>&nbsp;
                <i>optional</i> &nbsp;
                <input type="text" style="display: none"
                       name="national_id" data-endpoint="POSTapi-auth-profile"
                       value="n"
                       data-component="body">
                <br>
                <p>Must not be greater than 191 characters. Example: <code>n</code></p>
            </div>
            <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>residence_city_id</code></b>&nbsp;&nbsp;
                <small>integer</small>&nbsp;
                <i>optional</i> &nbsp;
                <input type="number" style="display: none"
                       step="any" name="residence_city_id" data-endpoint="POSTapi-auth-profile"
                       value="67"
                       data-component="body">
                <br>
                <p>Must be at least 1. Example: <code>67</code></p>
            </div>
            <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>residence_province_id</code></b>&nbsp;&nbsp;
                <small>integer</small>&nbsp;
                <i>optional</i> &nbsp;
                <input type="number" style="display: none"
                       step="any" name="residence_province_id" data-endpoint="POSTapi-auth-profile"
                       value="66"
                       data-component="body">
                <br>
                <p>Must be at least 1. Example: <code>66</code></p>
            </div>
        </form>

        <h2 id="endpoints-GETapi-auth-user">GET api/auth/user</h2>

        <p>
        </p>


        <span id="example-requests-GETapi-auth-user">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://wezone.test/api/auth/user" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://wezone.test/api/auth/user"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

        <span id="example-responses-GETapi-auth-user">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
        <span id="execution-results-GETapi-auth-user" hidden>
    <blockquote>Received response<span
            id="execution-response-status-GETapi-auth-user"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-auth-user"
                            data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
        <span id="execution-error-GETapi-auth-user" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-auth-user">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
        <form id="form-GETapi-auth-user" data-method="GET"
              data-path="api/auth/user"
              data-authed="0"
              data-hasfiles="0"
              data-isarraybody="0"
              autocomplete="off"
              onsubmit="event.preventDefault(); executeTryOut('GETapi-auth-user', this);">
            <h3>
                Request&nbsp;&nbsp;&nbsp;
                <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-auth-user"
                        onclick="tryItOut('GETapi-auth-user');">Try it out ⚡
                </button>
                <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-auth-user"
                        onclick="cancelTryOut('GETapi-auth-user');" hidden>Cancel 🛑
                </button>&nbsp;&nbsp;
                <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-auth-user"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                </button>
            </h3>
            <p>
                <small class="badge badge-green">GET</small>
                <b><code>api/auth/user</code></b>
            </p>
            <h4 class="fancy-heading-panel"><b>Headers</b></h4>
            <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                &nbsp;
                &nbsp;
                <input type="text" style="display: none"
                       name="Content-Type" data-endpoint="GETapi-auth-user"
                       value="application/json"
                       data-component="header">
                <br>
                <p>Example: <code>application/json</code></p>
            </div>
            <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                &nbsp;
                &nbsp;
                <input type="text" style="display: none"
                       name="Accept" data-endpoint="GETapi-auth-user"
                       value="application/json"
                       data-component="header">
                <br>
                <p>Example: <code>application/json</code></p>
            </div>
        </form>

        <h2 id="endpoints-POSTapi-auth-user">POST api/auth/user</h2>

        <p>
        </p>


        <span id="example-requests-POSTapi-auth-user">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://wezone.test/api/auth/user" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"username\": \"b\",
    \"email\": \"zbailey@example.net\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://wezone.test/api/auth/user"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "username": "b",
    "email": "zbailey@example.net"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

        <span id="example-responses-POSTapi-auth-user">
</span>
        <span id="execution-results-POSTapi-auth-user" hidden>
    <blockquote>Received response<span
            id="execution-response-status-POSTapi-auth-user"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-auth-user"
                            data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
        <span id="execution-error-POSTapi-auth-user" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-auth-user">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
        <form id="form-POSTapi-auth-user" data-method="POST"
              data-path="api/auth/user"
              data-authed="0"
              data-hasfiles="0"
              data-isarraybody="0"
              autocomplete="off"
              onsubmit="event.preventDefault(); executeTryOut('POSTapi-auth-user', this);">
            <h3>
                Request&nbsp;&nbsp;&nbsp;
                <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-POSTapi-auth-user"
                        onclick="tryItOut('POSTapi-auth-user');">Try it out ⚡
                </button>
                <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-POSTapi-auth-user"
                        onclick="cancelTryOut('POSTapi-auth-user');" hidden>Cancel 🛑
                </button>&nbsp;&nbsp;
                <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-POSTapi-auth-user"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                </button>
            </h3>
            <p>
                <small class="badge badge-black">POST</small>
                <b><code>api/auth/user</code></b>
            </p>
            <h4 class="fancy-heading-panel"><b>Headers</b></h4>
            <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                &nbsp;
                &nbsp;
                <input type="text" style="display: none"
                       name="Content-Type" data-endpoint="POSTapi-auth-user"
                       value="application/json"
                       data-component="header">
                <br>
                <p>Example: <code>application/json</code></p>
            </div>
            <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                &nbsp;
                &nbsp;
                <input type="text" style="display: none"
                       name="Accept" data-endpoint="POSTapi-auth-user"
                       value="application/json"
                       data-component="header">
                <br>
                <p>Example: <code>application/json</code></p>
            </div>
            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
            <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>username</code></b>&nbsp;&nbsp;
                <small>string</small>&nbsp;
                <i>optional</i> &nbsp;
                <input type="text" style="display: none"
                       name="username" data-endpoint="POSTapi-auth-user"
                       value="b"
                       data-component="body">
                <br>
                <p>Must not be greater than 191 characters. Example: <code>b</code></p>
            </div>
            <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
                <small>string</small>&nbsp;
                <i>optional</i> &nbsp;
                <input type="text" style="display: none"
                       name="email" data-endpoint="POSTapi-auth-user"
                       value="zbailey@example.net"
                       data-component="body">
                <br>
                <p>Must be a valid email address. Must not be greater than 191 characters. Example: <code>zbailey@example.net</code>
                </p>
            </div>
        </form>


    </div>
    <div class="dark-box">
        <div class="lang-selector">
            <button type="button" class="lang-button" data-language-name="bash">bash</button>
            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
        </div>
    </div>
</div>
</body>
</html>
