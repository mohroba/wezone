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
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "https://api.wezone.app";
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
                    <ul id="tocify-header-ad-attribute-definitions" class="tocify-header">
                <li class="tocify-item level-1" data-unique="ad-attribute-definitions">
                    <a href="#ad-attribute-definitions">Ad Attribute Definitions</a>
                </li>
                                    <ul id="tocify-subheader-ad-attribute-definitions" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="ad-attribute-definitions-GETapi-ad-attribute-definitions">
                                <a href="#ad-attribute-definitions-GETapi-ad-attribute-definitions">List attribute definitions</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ad-attribute-definitions-POSTapi-ad-attribute-definitions">
                                <a href="#ad-attribute-definitions-POSTapi-ad-attribute-definitions">Create an attribute definition</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ad-attribute-definitions-GETapi-ad-attribute-definitions--ad_attribute_definition_id-">
                                <a href="#ad-attribute-definitions-GETapi-ad-attribute-definitions--ad_attribute_definition_id-">Show an attribute definition</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ad-attribute-definitions-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update">
                                <a href="#ad-attribute-definitions-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update">Update an attribute definition</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ad-attribute-definitions-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete">
                                <a href="#ad-attribute-definitions-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete">Delete an attribute definition</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-ad-attribute-groups" class="tocify-header">
                <li class="tocify-item level-1" data-unique="ad-attribute-groups">
                    <a href="#ad-attribute-groups">Ad Attribute Groups</a>
                </li>
                                    <ul id="tocify-subheader-ad-attribute-groups" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="ad-attribute-groups-GETapi-ad-attribute-groups">
                                <a href="#ad-attribute-groups-GETapi-ad-attribute-groups">List attribute groups</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ad-attribute-groups-POSTapi-ad-attribute-groups">
                                <a href="#ad-attribute-groups-POSTapi-ad-attribute-groups">Create an attribute group</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ad-attribute-groups-GETapi-ad-attribute-groups--ad_attribute_group_id-">
                                <a href="#ad-attribute-groups-GETapi-ad-attribute-groups--ad_attribute_group_id-">Show an attribute group</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ad-attribute-groups-POSTapi-ad-attribute-groups--ad_attribute_group_id--update">
                                <a href="#ad-attribute-groups-POSTapi-ad-attribute-groups--ad_attribute_group_id--update">Update an attribute group</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ad-attribute-groups-POSTapi-ad-attribute-groups--ad_attribute_group_id--delete">
                                <a href="#ad-attribute-groups-POSTapi-ad-attribute-groups--ad_attribute_group_id--delete">Delete an attribute group</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-ad-attribute-values" class="tocify-header">
                <li class="tocify-item level-1" data-unique="ad-attribute-values">
                    <a href="#ad-attribute-values">Ad Attribute Values</a>
                </li>
                                    <ul id="tocify-subheader-ad-attribute-values" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="ad-attribute-values-GETapi-ad-attribute-values">
                                <a href="#ad-attribute-values-GETapi-ad-attribute-values">List attribute values</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ad-attribute-values-POSTapi-ad-attribute-values">
                                <a href="#ad-attribute-values-POSTapi-ad-attribute-values">Create an attribute value</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ad-attribute-values-GETapi-ad-attribute-values--ad_attribute_value_id-">
                                <a href="#ad-attribute-values-GETapi-ad-attribute-values--ad_attribute_value_id-">Show an attribute value</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ad-attribute-values-POSTapi-ad-attribute-values--ad_attribute_value_id--update">
                                <a href="#ad-attribute-values-POSTapi-ad-attribute-values--ad_attribute_value_id--update">Update an attribute value</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ad-attribute-values-POSTapi-ad-attribute-values--ad_attribute_value_id--delete">
                                <a href="#ad-attribute-values-POSTapi-ad-attribute-values--ad_attribute_value_id--delete">Delete an attribute value</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-ad-categories" class="tocify-header">
                <li class="tocify-item level-1" data-unique="ad-categories">
                    <a href="#ad-categories">Ad Categories</a>
                </li>
                                    <ul id="tocify-subheader-ad-categories" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="ad-categories-GETapi-ad-categories">
                                <a href="#ad-categories-GETapi-ad-categories">List ad categories</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ad-categories-POSTapi-ad-categories">
                                <a href="#ad-categories-POSTapi-ad-categories">Create a category</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ad-categories-GETapi-ad-categories--ad_category_id-">
                                <a href="#ad-categories-GETapi-ad-categories--ad_category_id-">Show a category</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ad-categories-POSTapi-ad-categories--ad_category_id--update">
                                <a href="#ad-categories-POSTapi-ad-categories--ad_category_id--update">Update a category</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ad-categories-POSTapi-ad-categories--ad_category_id--delete">
                                <a href="#ad-categories-POSTapi-ad-categories--ad_category_id--delete">Delete a category</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-ads" class="tocify-header">
                <li class="tocify-item level-1" data-unique="ads">
                    <a href="#ads">Ads</a>
                </li>
                                    <ul id="tocify-subheader-ads" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="ads-GETapi-ads">
                                <a href="#ads-GETapi-ads">List ads</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ads-POSTapi-ads">
                                <a href="#ads-POSTapi-ads">Create an ad</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ads-GETapi-ads--ad_id-">
                                <a href="#ads-GETapi-ads--ad_id-">Show ad details</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ads-POSTapi-ads--ad_id--update">
                                <a href="#ads-POSTapi-ads--ad_id--update">Update an ad</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ads-POSTapi-ads--ad_id--delete">
                                <a href="#ads-POSTapi-ads--ad_id--delete">Delete an ad</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-auth" class="tocify-header">
                <li class="tocify-item level-1" data-unique="auth">
                    <a href="#auth">Auth</a>
                </li>
                                    <ul id="tocify-subheader-auth" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="auth-POSTapi-auth-otp-send">
                                <a href="#auth-POSTapi-auth-otp-send">POST api/auth/otp/send</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="auth-POSTapi-auth-otp-verify">
                                <a href="#auth-POSTapi-auth-otp-verify">POST api/auth/otp/verify</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="auth-GETapi-auth-profile">
                                <a href="#auth-GETapi-auth-profile">GET api/auth/profile</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="auth-POSTapi-auth-profile">
                                <a href="#auth-POSTapi-auth-profile">POST api/auth/profile</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="auth-GETapi-auth-user">
                                <a href="#auth-GETapi-auth-user">GET api/auth/user</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="auth-POSTapi-auth-user">
                                <a href="#auth-POSTapi-auth-user">POST api/auth/user</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-geography" class="tocify-header">
                <li class="tocify-item level-1" data-unique="geography">
                    <a href="#geography">Geography</a>
                </li>
                                    <ul id="tocify-subheader-geography" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="geography-GETapi-geography-countries">
                                <a href="#geography-GETapi-geography-countries">List countries</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="geography-GETapi-geography-countries--country_id-">
                                <a href="#geography-GETapi-geography-countries--country_id-">Get a country</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="geography-GETapi-geography-provinces">
                                <a href="#geography-GETapi-geography-provinces">List provinces</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="geography-GETapi-geography-provinces--province_id-">
                                <a href="#geography-GETapi-geography-provinces--province_id-">Get a province</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="geography-GETapi-geography-provinces--province_id--cities">
                                <a href="#geography-GETapi-geography-provinces--province_id--cities">List a province's cities</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="geography-GETapi-geography-cities">
                                <a href="#geography-GETapi-geography-cities">List cities</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="geography-GETapi-geography-cities--city_id-">
                                <a href="#geography-GETapi-geography-cities--city_id-">Get a city</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="geography-GETapi-geography-locations-lookup">
                                <a href="#geography-GETapi-geography-locations-lookup">Lookup nearby locations</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="geography-GETapi-geography-locations-user-city">
                                <a href="#geography-GETapi-geography-locations-user-city">Resolve user's city</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="geography-GETapi-geography-locations-nearby-cities">
                                <a href="#geography-GETapi-geography-locations-nearby-cities">Nearby cities</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-ads-review" class="tocify-header">
                <li class="tocify-item level-1" data-unique="ads-review">
                    <a href="#ads-review">Ads Review</a>
                </li>
                                    <ul id="tocify-subheader-ads-review" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="ads-review-POSTapi-kpi-devices-register">
                                <a href="#ads-review-POSTapi-kpi-devices-register">POST api/kpi/devices/register</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ads-review-POSTapi-kpi-devices-heartbeat">
                                <a href="#ads-review-POSTapi-kpi-devices-heartbeat">POST api/kpi/devices/heartbeat</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ads-review-POSTapi-kpi-installations">
                                <a href="#ads-review-POSTapi-kpi-installations">POST api/kpi/installations</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ads-review-POSTapi-kpi-uninstallations">
                                <a href="#ads-review-POSTapi-kpi-uninstallations">POST api/kpi/uninstallations</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ads-review-POSTapi-kpi-sessions">
                                <a href="#ads-review-POSTapi-kpi-sessions">POST api/kpi/sessions</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ads-review-POSTapi-kpi-sessions--session_session_uuid--update">
                                <a href="#ads-review-POSTapi-kpi-sessions--session_session_uuid--update">POST api/kpi/sessions/{session_session_uuid}/update</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="ads-review-POSTapi-kpi-events">
                                <a href="#ads-review-POSTapi-kpi-events">POST api/kpi/events</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-settings" class="tocify-header">
                <li class="tocify-item level-1" data-unique="settings">
                    <a href="#settings">Settings</a>
                </li>
                                    <ul id="tocify-subheader-settings" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="settings-GETapi-v1-settings">
                                <a href="#settings-GETapi-v1-settings">Display a listing of the resource.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="settings-POSTapi-v1-settings">
                                <a href="#settings-POSTapi-v1-settings">Store a newly created resource in storage.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="settings-GETapi-v1-settings--id-">
                                <a href="#settings-GETapi-v1-settings--id-">Show the specified resource.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="settings-PUTapi-v1-settings--id-">
                                <a href="#settings-PUTapi-v1-settings--id-">Update the specified resource in storage.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="settings-DELETEapi-v1-settings--id-">
                                <a href="#settings-DELETEapi-v1-settings--id-">Remove the specified resource from storage.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-users" class="tocify-header">
                <li class="tocify-item level-1" data-unique="users">
                    <a href="#users">Users</a>
                </li>
                                    <ul id="tocify-subheader-users" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="users-GETapi-users--user_id--followers">
                                <a href="#users-GETapi-users--user_id--followers">GET api/users/{user_id}/followers</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="users-GETapi-users">
                                <a href="#users-GETapi-users">GET api/users</a>
                            </li>
                                                    <li class="tocify-item level-2" data-unique="users-POSTapi-users--user_id--follow">
                                <a href="#users-POSTapi-users--user_id--follow">Follow a user.</a>
                            </li>
                                                    <li class="tocify-item level-2" data-unique="users-POSTapi-users--user_id--unfollow">
                                <a href="#users-POSTapi-users--user_id--unfollow">Unfollow a user.</a>
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
        <li>Last updated: October 5, 2025</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<aside>
    <strong>Base URL</strong>: <code>https://api.wezone.app</code>
</aside>
<pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>Most endpoints require a valid OAuth access token issued by Laravel Passport.</p>
<p>Send the token in the Authorization header using the Bearer scheme: Authorization: Bearer {token}. Obtain tokens by completing the mobile OTP verification flow.</p>

        <h1 id="ad-attribute-definitions">Ad Attribute Definitions</h1>

    <p>Retrieve attribute definitions optionally filtered by group.</p>

                                <h2 id="ad-attribute-definitions-GETapi-ad-attribute-definitions">List attribute definitions</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-ad-attribute-definitions">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/ad-attribute-definitions?group_id=4&amp;per_page=25&amp;without_pagination=" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ad-attribute-definitions"
);

const params = {
    "group_id": "4",
    "per_page": "25",
    "without_pagination": "0",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ad-attribute-definitions">
            <blockquote>
            <p>Example response (200):</p>
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
    &quot;data&quot;: [],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;https://api.wezone.app/api/ad-attribute-definitions?group_id=4&amp;per_page=25&amp;without_pagination=0&amp;page=1&quot;,
        &quot;last&quot;: &quot;https://api.wezone.app/api/ad-attribute-definitions?group_id=4&amp;per_page=25&amp;without_pagination=0&amp;page=1&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;from&quot;: null,
        &quot;last_page&quot;: 1,
        &quot;links&quot;: [
            {
                &quot;url&quot;: null,
                &quot;label&quot;: &quot;&amp;laquo; Previous&quot;,
                &quot;page&quot;: null,
                &quot;active&quot;: false
            },
            {
                &quot;url&quot;: &quot;https://api.wezone.app/api/ad-attribute-definitions?group_id=4&amp;per_page=25&amp;without_pagination=0&amp;page=1&quot;,
                &quot;label&quot;: &quot;1&quot;,
                &quot;page&quot;: 1,
                &quot;active&quot;: true
            },
            {
                &quot;url&quot;: null,
                &quot;label&quot;: &quot;Next &amp;raquo;&quot;,
                &quot;page&quot;: null,
                &quot;active&quot;: false
            }
        ],
        &quot;path&quot;: &quot;https://api.wezone.app/api/ad-attribute-definitions&quot;,
        &quot;per_page&quot;: 25,
        &quot;to&quot;: null,
        &quot;total&quot;: 0
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ad-attribute-definitions" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ad-attribute-definitions"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ad-attribute-definitions"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-ad-attribute-definitions" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ad-attribute-definitions">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-ad-attribute-definitions" data-method="GET"
      data-path="api/ad-attribute-definitions"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ad-attribute-definitions', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ad-attribute-definitions"
                    onclick="tryItOut('GETapi-ad-attribute-definitions');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ad-attribute-definitions"
                    onclick="cancelTryOut('GETapi-ad-attribute-definitions');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ad-attribute-definitions"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ad-attribute-definitions</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-ad-attribute-definitions"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-ad-attribute-definitions"
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
                              name="Accept"                data-endpoint="GETapi-ad-attribute-definitions"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>group_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="group_id"                data-endpoint="GETapi-ad-attribute-definitions"
               value="4"
               data-component="query">
    <br>
<p>Limit results to a specific attribute group. Example: <code>4</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-ad-attribute-definitions"
               value="25"
               data-component="query">
    <br>
<p>Number of results per page, up to 200. Example: <code>25</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>without_pagination</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="GETapi-ad-attribute-definitions" style="display: none">
            <input type="radio" name="without_pagination"
                   value="1"
                   data-endpoint="GETapi-ad-attribute-definitions"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-ad-attribute-definitions" style="display: none">
            <input type="radio" name="without_pagination"
                   value="0"
                   data-endpoint="GETapi-ad-attribute-definitions"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Set to true to return all definitions without pagination. Example: <code>false</code></p>
            </div>
                </form>

                    <h2 id="ad-attribute-definitions-POSTapi-ad-attribute-definitions">Create an attribute definition</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-ad-attribute-definitions">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/ad-attribute-definitions" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"group_id\": 2,
    \"key\": \"engine_volume\",
    \"label\": \"Engine volume\",
    \"help_text\": \"Specify the displacement in liters.\",
    \"data_type\": \"decimal\",
    \"unit\": \"L\",
    \"options\": {
        \"min\": 1,
        \"max\": 5
    },
    \"is_required\": false,
    \"is_filterable\": false,
    \"is_searchable\": false,
    \"validation_rules\": \"numeric|min:0.5|max:5\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ad-attribute-definitions"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "group_id": 2,
    "key": "engine_volume",
    "label": "Engine volume",
    "help_text": "Specify the displacement in liters.",
    "data_type": "decimal",
    "unit": "L",
    "options": {
        "min": 1,
        "max": 5
    },
    "is_required": false,
    "is_filterable": false,
    "is_searchable": false,
    "validation_rules": "numeric|min:0.5|max:5"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ad-attribute-definitions">
</span>
<span id="execution-results-POSTapi-ad-attribute-definitions" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ad-attribute-definitions"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ad-attribute-definitions"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ad-attribute-definitions" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ad-attribute-definitions">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ad-attribute-definitions" data-method="POST"
      data-path="api/ad-attribute-definitions"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ad-attribute-definitions', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ad-attribute-definitions"
                    onclick="tryItOut('POSTapi-ad-attribute-definitions');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ad-attribute-definitions"
                    onclick="cancelTryOut('POSTapi-ad-attribute-definitions');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ad-attribute-definitions"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ad-attribute-definitions</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ad-attribute-definitions"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ad-attribute-definitions"
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
                              name="Accept"                data-endpoint="POSTapi-ad-attribute-definitions"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>group_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="group_id"                data-endpoint="POSTapi-ad-attribute-definitions"
               value="2"
               data-component="body">
    <br>
<p>Identifier of the attribute group this definition belongs to. The <code>id</code> of an existing record in the ad_attribute_groups table. Example: <code>2</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>key</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="key"                data-endpoint="POSTapi-ad-attribute-definitions"
               value="engine_volume"
               data-component="body">
    <br>
<p>Unique machine-friendly key for the attribute. Must not be greater than 255 characters. Example: <code>engine_volume</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>label</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="label"                data-endpoint="POSTapi-ad-attribute-definitions"
               value="Engine volume"
               data-component="body">
    <br>
<p>Human readable label for the attribute. Must not be greater than 255 characters. Example: <code>Engine volume</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>help_text</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="help_text"                data-endpoint="POSTapi-ad-attribute-definitions"
               value="Specify the displacement in liters."
               data-component="body">
    <br>
<p>Helper text to guide form inputs. Example: <code>Specify the displacement in liters.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>data_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="data_type"                data-endpoint="POSTapi-ad-attribute-definitions"
               value="decimal"
               data-component="body">
    <br>
<p>Datatype expected for the attribute value. Example: <code>decimal</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>string</code></li> <li><code>integer</code></li> <li><code>decimal</code></li> <li><code>boolean</code></li> <li><code>enum</code></li> <li><code>json</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>unit</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="unit"                data-endpoint="POSTapi-ad-attribute-definitions"
               value="L"
               data-component="body">
    <br>
<p>Unit displayed next to the value. Must not be greater than 255 characters. Example: <code>L</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>options</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="options"                data-endpoint="POSTapi-ad-attribute-definitions"
               value=""
               data-component="body">
    <br>
<p>Available options or constraints for the attribute.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_required</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-ad-attribute-definitions" style="display: none">
            <input type="radio" name="is_required"
                   value="true"
                   data-endpoint="POSTapi-ad-attribute-definitions"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-ad-attribute-definitions" style="display: none">
            <input type="radio" name="is_required"
                   value="false"
                   data-endpoint="POSTapi-ad-attribute-definitions"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Whether the attribute must be provided when creating ads. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_filterable</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-ad-attribute-definitions" style="display: none">
            <input type="radio" name="is_filterable"
                   value="true"
                   data-endpoint="POSTapi-ad-attribute-definitions"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-ad-attribute-definitions" style="display: none">
            <input type="radio" name="is_filterable"
                   value="false"
                   data-endpoint="POSTapi-ad-attribute-definitions"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Whether the attribute can be used as a filter in listings. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_searchable</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-ad-attribute-definitions" style="display: none">
            <input type="radio" name="is_searchable"
                   value="true"
                   data-endpoint="POSTapi-ad-attribute-definitions"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-ad-attribute-definitions" style="display: none">
            <input type="radio" name="is_searchable"
                   value="false"
                   data-endpoint="POSTapi-ad-attribute-definitions"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Whether the attribute contributes to search indexes. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>validation_rules</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="validation_rules"                data-endpoint="POSTapi-ad-attribute-definitions"
               value="numeric|min:0.5|max:5"
               data-component="body">
    <br>
<p>Laravel validation rules applied to the attribute value. Example: <code>numeric|min:0.5|max:5</code></p>
        </div>
        </form>

                    <h2 id="ad-attribute-definitions-GETapi-ad-attribute-definitions--ad_attribute_definition_id-">Show an attribute definition</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-ad-attribute-definitions--ad_attribute_definition_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/ad-attribute-definitions/architecto" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ad-attribute-definitions/architecto"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ad-attribute-definitions--ad_attribute_definition_id-">
            <blockquote>
            <p>Example response (404):</p>
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
    &quot;message&quot;: &quot;No query results for model [Modules\\Ad\\Models\\AdAttributeDefinition] architecto&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ad-attribute-definitions--ad_attribute_definition_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ad-attribute-definitions--ad_attribute_definition_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ad-attribute-definitions--ad_attribute_definition_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-ad-attribute-definitions--ad_attribute_definition_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ad-attribute-definitions--ad_attribute_definition_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-ad-attribute-definitions--ad_attribute_definition_id-" data-method="GET"
      data-path="api/ad-attribute-definitions/{ad_attribute_definition_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ad-attribute-definitions--ad_attribute_definition_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ad-attribute-definitions--ad_attribute_definition_id-"
                    onclick="tryItOut('GETapi-ad-attribute-definitions--ad_attribute_definition_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ad-attribute-definitions--ad_attribute_definition_id-"
                    onclick="cancelTryOut('GETapi-ad-attribute-definitions--ad_attribute_definition_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ad-attribute-definitions--ad_attribute_definition_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ad-attribute-definitions/{ad_attribute_definition_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-ad-attribute-definitions--ad_attribute_definition_id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-ad-attribute-definitions--ad_attribute_definition_id-"
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
                              name="Accept"                data-endpoint="GETapi-ad-attribute-definitions--ad_attribute_definition_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ad_attribute_definition_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ad_attribute_definition_id"                data-endpoint="GETapi-ad-attribute-definitions--ad_attribute_definition_id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ad attribute definition. Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="ad-attribute-definitions-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update">Update an attribute definition</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/ad-attribute-definitions/architecto/update" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"group_id\": 2,
    \"key\": \"engine_volume\",
    \"label\": \"Engine volume\",
    \"help_text\": \"Specify the displacement in liters.\",
    \"data_type\": \"decimal\",
    \"unit\": \"L\",
    \"options\": {
        \"min\": 1,
        \"max\": 5
    },
    \"is_required\": false,
    \"is_filterable\": false,
    \"is_searchable\": false,
    \"validation_rules\": \"numeric|min:0.5|max:5\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ad-attribute-definitions/architecto/update"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "group_id": 2,
    "key": "engine_volume",
    "label": "Engine volume",
    "help_text": "Specify the displacement in liters.",
    "data_type": "decimal",
    "unit": "L",
    "options": {
        "min": 1,
        "max": 5
    },
    "is_required": false,
    "is_filterable": false,
    "is_searchable": false,
    "validation_rules": "numeric|min:0.5|max:5"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update">
</span>
<span id="execution-results-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update" data-method="POST"
      data-path="api/ad-attribute-definitions/{ad_attribute_definition_id}/update"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
                    onclick="tryItOut('POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
                    onclick="cancelTryOut('POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ad-attribute-definitions/{ad_attribute_definition_id}/update</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
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
                              name="Accept"                data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ad_attribute_definition_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ad_attribute_definition_id"                data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ad attribute definition. Example: <code>architecto</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>group_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="group_id"                data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
               value="2"
               data-component="body">
    <br>
<p>Identifier of the attribute group this definition belongs to. The <code>id</code> of an existing record in the ad_attribute_groups table. Example: <code>2</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>key</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="key"                data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
               value="engine_volume"
               data-component="body">
    <br>
<p>Unique machine-friendly key for the attribute. Must not be greater than 255 characters. Example: <code>engine_volume</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>label</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="label"                data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
               value="Engine volume"
               data-component="body">
    <br>
<p>Human readable label for the attribute. Must not be greater than 255 characters. Example: <code>Engine volume</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>help_text</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="help_text"                data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
               value="Specify the displacement in liters."
               data-component="body">
    <br>
<p>Helper text to guide form inputs. Example: <code>Specify the displacement in liters.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>data_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="data_type"                data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
               value="decimal"
               data-component="body">
    <br>
<p>Datatype expected for the attribute value. Example: <code>decimal</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>string</code></li> <li><code>integer</code></li> <li><code>decimal</code></li> <li><code>boolean</code></li> <li><code>enum</code></li> <li><code>json</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>unit</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="unit"                data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
               value="L"
               data-component="body">
    <br>
<p>Unit displayed next to the value. Must not be greater than 255 characters. Example: <code>L</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>options</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="options"                data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
               value=""
               data-component="body">
    <br>
<p>Available options or constraints for the attribute.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_required</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update" style="display: none">
            <input type="radio" name="is_required"
                   value="true"
                   data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update" style="display: none">
            <input type="radio" name="is_required"
                   value="false"
                   data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Whether the attribute must be provided when creating ads. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_filterable</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update" style="display: none">
            <input type="radio" name="is_filterable"
                   value="true"
                   data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update" style="display: none">
            <input type="radio" name="is_filterable"
                   value="false"
                   data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Whether the attribute can be used as a filter in listings. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_searchable</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update" style="display: none">
            <input type="radio" name="is_searchable"
                   value="true"
                   data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update" style="display: none">
            <input type="radio" name="is_searchable"
                   value="false"
                   data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Whether the attribute contributes to search indexes. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>validation_rules</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="validation_rules"                data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--update"
               value="numeric|min:0.5|max:5"
               data-component="body">
    <br>
<p>Laravel validation rules applied to the attribute value. Example: <code>numeric|min:0.5|max:5</code></p>
        </div>
        </form>

                    <h2 id="ad-attribute-definitions-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete">Delete an attribute definition</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/ad-attribute-definitions/architecto/delete" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ad-attribute-definitions/architecto/delete"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete">
</span>
<span id="execution-results-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete" data-method="POST"
      data-path="api/ad-attribute-definitions/{ad_attribute_definition_id}/delete"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete"
                    onclick="tryItOut('POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete"
                    onclick="cancelTryOut('POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ad-attribute-definitions/{ad_attribute_definition_id}/delete</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete"
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
                              name="Accept"                data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ad_attribute_definition_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ad_attribute_definition_id"                data-endpoint="POSTapi-ad-attribute-definitions--ad_attribute_definition_id--delete"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ad attribute definition. Example: <code>architecto</code></p>
            </div>
                    </form>

                <h1 id="ad-attribute-groups">Ad Attribute Groups</h1>

    <p>Retrieve attribute groups filtered by advertisable type or category.</p>

                                <h2 id="ad-attribute-groups-GETapi-ad-attribute-groups">List attribute groups</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-ad-attribute-groups">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/ad-attribute-groups?advertisable_type=Modules%5C%5CAd%5C%5CModels%5C%5CAdCar&amp;category_id=8&amp;per_page=25&amp;without_pagination=" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ad-attribute-groups"
);

const params = {
    "advertisable_type": "Modules\\Ad\\Models\\AdCar",
    "category_id": "8",
    "per_page": "25",
    "without_pagination": "0",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ad-attribute-groups">
            <blockquote>
            <p>Example response (200):</p>
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
    &quot;data&quot;: [],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;https://api.wezone.app/api/ad-attribute-groups?advertisable_type=Modules%5C%5CAd%5C%5CModels%5C%5CAdCar&amp;category_id=8&amp;per_page=25&amp;without_pagination=0&amp;page=1&quot;,
        &quot;last&quot;: &quot;https://api.wezone.app/api/ad-attribute-groups?advertisable_type=Modules%5C%5CAd%5C%5CModels%5C%5CAdCar&amp;category_id=8&amp;per_page=25&amp;without_pagination=0&amp;page=1&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;from&quot;: null,
        &quot;last_page&quot;: 1,
        &quot;links&quot;: [
            {
                &quot;url&quot;: null,
                &quot;label&quot;: &quot;&amp;laquo; Previous&quot;,
                &quot;page&quot;: null,
                &quot;active&quot;: false
            },
            {
                &quot;url&quot;: &quot;https://api.wezone.app/api/ad-attribute-groups?advertisable_type=Modules%5C%5CAd%5C%5CModels%5C%5CAdCar&amp;category_id=8&amp;per_page=25&amp;without_pagination=0&amp;page=1&quot;,
                &quot;label&quot;: &quot;1&quot;,
                &quot;page&quot;: 1,
                &quot;active&quot;: true
            },
            {
                &quot;url&quot;: null,
                &quot;label&quot;: &quot;Next &amp;raquo;&quot;,
                &quot;page&quot;: null,
                &quot;active&quot;: false
            }
        ],
        &quot;path&quot;: &quot;https://api.wezone.app/api/ad-attribute-groups&quot;,
        &quot;per_page&quot;: 25,
        &quot;to&quot;: null,
        &quot;total&quot;: 0
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ad-attribute-groups" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ad-attribute-groups"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ad-attribute-groups"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-ad-attribute-groups" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ad-attribute-groups">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-ad-attribute-groups" data-method="GET"
      data-path="api/ad-attribute-groups"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ad-attribute-groups', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ad-attribute-groups"
                    onclick="tryItOut('GETapi-ad-attribute-groups');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ad-attribute-groups"
                    onclick="cancelTryOut('GETapi-ad-attribute-groups');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ad-attribute-groups"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ad-attribute-groups</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-ad-attribute-groups"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-ad-attribute-groups"
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
                              name="Accept"                data-endpoint="GETapi-ad-attribute-groups"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>advertisable_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="advertisable_type"                data-endpoint="GETapi-ad-attribute-groups"
               value="Modules\\Ad\\Models\\AdCar"
               data-component="query">
    <br>
<p>Filter by advertisable class name. Example: <code>Modules\\Ad\\Models\\AdCar</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>category_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="category_id"                data-endpoint="GETapi-ad-attribute-groups"
               value="8"
               data-component="query">
    <br>
<p>Filter groups scoped to the given category. Example: <code>8</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-ad-attribute-groups"
               value="25"
               data-component="query">
    <br>
<p>Number of results per page, up to 200. Example: <code>25</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>without_pagination</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="GETapi-ad-attribute-groups" style="display: none">
            <input type="radio" name="without_pagination"
                   value="1"
                   data-endpoint="GETapi-ad-attribute-groups"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-ad-attribute-groups" style="display: none">
            <input type="radio" name="without_pagination"
                   value="0"
                   data-endpoint="GETapi-ad-attribute-groups"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Set to true to return all groups without pagination. Example: <code>false</code></p>
            </div>
                </form>

                    <h2 id="ad-attribute-groups-POSTapi-ad-attribute-groups">Create an attribute group</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-ad-attribute-groups">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/ad-attribute-groups" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Engine specifications\",
    \"advertisable_type\": \"Modules\\\\Ad\\\\Models\\\\AdCar\",
    \"category_id\": 7,
    \"display_order\": 1
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ad-attribute-groups"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Engine specifications",
    "advertisable_type": "Modules\\Ad\\Models\\AdCar",
    "category_id": 7,
    "display_order": 1
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ad-attribute-groups">
</span>
<span id="execution-results-POSTapi-ad-attribute-groups" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ad-attribute-groups"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ad-attribute-groups"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ad-attribute-groups" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ad-attribute-groups">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ad-attribute-groups" data-method="POST"
      data-path="api/ad-attribute-groups"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ad-attribute-groups', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ad-attribute-groups"
                    onclick="tryItOut('POSTapi-ad-attribute-groups');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ad-attribute-groups"
                    onclick="cancelTryOut('POSTapi-ad-attribute-groups');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ad-attribute-groups"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ad-attribute-groups</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ad-attribute-groups"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ad-attribute-groups"
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
                              name="Accept"                data-endpoint="POSTapi-ad-attribute-groups"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-ad-attribute-groups"
               value="Engine specifications"
               data-component="body">
    <br>
<p>Display name of the attribute group. Must not be greater than 255 characters. Example: <code>Engine specifications</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>advertisable_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="advertisable_type"                data-endpoint="POSTapi-ad-attribute-groups"
               value="Modules\Ad\Models\AdCar"
               data-component="body">
    <br>
<p>Advertisable model class this group applies to. Example: <code>Modules\Ad\Models\AdCar</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>Modules\Ad\Models\AdCar</code></li> <li><code>Modules\Ad\Models\AdRealEstate</code></li> <li><code>Modules\Ad\Models\AdJob</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>category_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="category_id"                data-endpoint="POSTapi-ad-attribute-groups"
               value="7"
               data-component="body">
    <br>
<p>Optional category scope for the group. The <code>id</code> of an existing record in the ad_categories table. Example: <code>7</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>display_order</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="display_order"                data-endpoint="POSTapi-ad-attribute-groups"
               value="1"
               data-component="body">
    <br>
<p>Numeric sorting weight for UI rendering. Must be at least 0. Example: <code>1</code></p>
        </div>
        </form>

                    <h2 id="ad-attribute-groups-GETapi-ad-attribute-groups--ad_attribute_group_id-">Show an attribute group</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-ad-attribute-groups--ad_attribute_group_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/ad-attribute-groups/architecto" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ad-attribute-groups/architecto"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ad-attribute-groups--ad_attribute_group_id-">
            <blockquote>
            <p>Example response (404):</p>
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
    &quot;message&quot;: &quot;No query results for model [Modules\\Ad\\Models\\AdAttributeGroup] architecto&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ad-attribute-groups--ad_attribute_group_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ad-attribute-groups--ad_attribute_group_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ad-attribute-groups--ad_attribute_group_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-ad-attribute-groups--ad_attribute_group_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ad-attribute-groups--ad_attribute_group_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-ad-attribute-groups--ad_attribute_group_id-" data-method="GET"
      data-path="api/ad-attribute-groups/{ad_attribute_group_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ad-attribute-groups--ad_attribute_group_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ad-attribute-groups--ad_attribute_group_id-"
                    onclick="tryItOut('GETapi-ad-attribute-groups--ad_attribute_group_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ad-attribute-groups--ad_attribute_group_id-"
                    onclick="cancelTryOut('GETapi-ad-attribute-groups--ad_attribute_group_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ad-attribute-groups--ad_attribute_group_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ad-attribute-groups/{ad_attribute_group_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-ad-attribute-groups--ad_attribute_group_id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-ad-attribute-groups--ad_attribute_group_id-"
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
                              name="Accept"                data-endpoint="GETapi-ad-attribute-groups--ad_attribute_group_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ad_attribute_group_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ad_attribute_group_id"                data-endpoint="GETapi-ad-attribute-groups--ad_attribute_group_id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ad attribute group. Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="ad-attribute-groups-POSTapi-ad-attribute-groups--ad_attribute_group_id--update">Update an attribute group</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-ad-attribute-groups--ad_attribute_group_id--update">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/ad-attribute-groups/architecto/update" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Engine specifications\",
    \"advertisable_type\": \"Modules\\\\Ad\\\\Models\\\\AdCar\",
    \"category_id\": 7,
    \"display_order\": 1
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ad-attribute-groups/architecto/update"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Engine specifications",
    "advertisable_type": "Modules\\Ad\\Models\\AdCar",
    "category_id": 7,
    "display_order": 1
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ad-attribute-groups--ad_attribute_group_id--update">
</span>
<span id="execution-results-POSTapi-ad-attribute-groups--ad_attribute_group_id--update" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ad-attribute-groups--ad_attribute_group_id--update"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ad-attribute-groups--ad_attribute_group_id--update"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ad-attribute-groups--ad_attribute_group_id--update" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ad-attribute-groups--ad_attribute_group_id--update">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ad-attribute-groups--ad_attribute_group_id--update" data-method="POST"
      data-path="api/ad-attribute-groups/{ad_attribute_group_id}/update"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ad-attribute-groups--ad_attribute_group_id--update', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ad-attribute-groups--ad_attribute_group_id--update"
                    onclick="tryItOut('POSTapi-ad-attribute-groups--ad_attribute_group_id--update');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ad-attribute-groups--ad_attribute_group_id--update"
                    onclick="cancelTryOut('POSTapi-ad-attribute-groups--ad_attribute_group_id--update');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ad-attribute-groups--ad_attribute_group_id--update"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ad-attribute-groups/{ad_attribute_group_id}/update</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ad-attribute-groups--ad_attribute_group_id--update"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ad-attribute-groups--ad_attribute_group_id--update"
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
                              name="Accept"                data-endpoint="POSTapi-ad-attribute-groups--ad_attribute_group_id--update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ad_attribute_group_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ad_attribute_group_id"                data-endpoint="POSTapi-ad-attribute-groups--ad_attribute_group_id--update"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ad attribute group. Example: <code>architecto</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-ad-attribute-groups--ad_attribute_group_id--update"
               value="Engine specifications"
               data-component="body">
    <br>
<p>Display name of the attribute group. Must not be greater than 255 characters. Example: <code>Engine specifications</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>advertisable_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="advertisable_type"                data-endpoint="POSTapi-ad-attribute-groups--ad_attribute_group_id--update"
               value="Modules\Ad\Models\AdCar"
               data-component="body">
    <br>
<p>Advertisable model class this group applies to. Example: <code>Modules\Ad\Models\AdCar</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>Modules\Ad\Models\AdCar</code></li> <li><code>Modules\Ad\Models\AdRealEstate</code></li> <li><code>Modules\Ad\Models\AdJob</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>category_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="category_id"                data-endpoint="POSTapi-ad-attribute-groups--ad_attribute_group_id--update"
               value="7"
               data-component="body">
    <br>
<p>Optional category scope for the group. The <code>id</code> of an existing record in the ad_categories table. Example: <code>7</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>display_order</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="display_order"                data-endpoint="POSTapi-ad-attribute-groups--ad_attribute_group_id--update"
               value="1"
               data-component="body">
    <br>
<p>Numeric sorting weight for UI rendering. Must be at least 0. Example: <code>1</code></p>
        </div>
        </form>

                    <h2 id="ad-attribute-groups-POSTapi-ad-attribute-groups--ad_attribute_group_id--delete">Delete an attribute group</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-ad-attribute-groups--ad_attribute_group_id--delete">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/ad-attribute-groups/architecto/delete" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ad-attribute-groups/architecto/delete"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ad-attribute-groups--ad_attribute_group_id--delete">
</span>
<span id="execution-results-POSTapi-ad-attribute-groups--ad_attribute_group_id--delete" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ad-attribute-groups--ad_attribute_group_id--delete"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ad-attribute-groups--ad_attribute_group_id--delete"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ad-attribute-groups--ad_attribute_group_id--delete" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ad-attribute-groups--ad_attribute_group_id--delete">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ad-attribute-groups--ad_attribute_group_id--delete" data-method="POST"
      data-path="api/ad-attribute-groups/{ad_attribute_group_id}/delete"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ad-attribute-groups--ad_attribute_group_id--delete', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ad-attribute-groups--ad_attribute_group_id--delete"
                    onclick="tryItOut('POSTapi-ad-attribute-groups--ad_attribute_group_id--delete');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ad-attribute-groups--ad_attribute_group_id--delete"
                    onclick="cancelTryOut('POSTapi-ad-attribute-groups--ad_attribute_group_id--delete');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ad-attribute-groups--ad_attribute_group_id--delete"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ad-attribute-groups/{ad_attribute_group_id}/delete</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ad-attribute-groups--ad_attribute_group_id--delete"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ad-attribute-groups--ad_attribute_group_id--delete"
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
                              name="Accept"                data-endpoint="POSTapi-ad-attribute-groups--ad_attribute_group_id--delete"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ad_attribute_group_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ad_attribute_group_id"                data-endpoint="POSTapi-ad-attribute-groups--ad_attribute_group_id--delete"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ad attribute group. Example: <code>architecto</code></p>
            </div>
                    </form>

                <h1 id="ad-attribute-values">Ad Attribute Values</h1>

    <p>Retrieve attribute values filtered by definition or advertisable linkage.</p>

                                <h2 id="ad-attribute-values-GETapi-ad-attribute-values">List attribute values</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-ad-attribute-values">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/ad-attribute-values?definition_id=12&amp;advertisable_type=Modules%5C%5CAd%5C%5CModels%5C%5CAdCar&amp;advertisable_id=34&amp;per_page=25&amp;without_pagination=" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ad-attribute-values"
);

const params = {
    "definition_id": "12",
    "advertisable_type": "Modules\\Ad\\Models\\AdCar",
    "advertisable_id": "34",
    "per_page": "25",
    "without_pagination": "0",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ad-attribute-values">
            <blockquote>
            <p>Example response (200):</p>
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
    &quot;data&quot;: [],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;https://api.wezone.app/api/ad-attribute-values?definition_id=12&amp;advertisable_type=Modules%5C%5CAd%5C%5CModels%5C%5CAdCar&amp;advertisable_id=34&amp;per_page=25&amp;without_pagination=0&amp;page=1&quot;,
        &quot;last&quot;: &quot;https://api.wezone.app/api/ad-attribute-values?definition_id=12&amp;advertisable_type=Modules%5C%5CAd%5C%5CModels%5C%5CAdCar&amp;advertisable_id=34&amp;per_page=25&amp;without_pagination=0&amp;page=1&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;from&quot;: null,
        &quot;last_page&quot;: 1,
        &quot;links&quot;: [
            {
                &quot;url&quot;: null,
                &quot;label&quot;: &quot;&amp;laquo; Previous&quot;,
                &quot;page&quot;: null,
                &quot;active&quot;: false
            },
            {
                &quot;url&quot;: &quot;https://api.wezone.app/api/ad-attribute-values?definition_id=12&amp;advertisable_type=Modules%5C%5CAd%5C%5CModels%5C%5CAdCar&amp;advertisable_id=34&amp;per_page=25&amp;without_pagination=0&amp;page=1&quot;,
                &quot;label&quot;: &quot;1&quot;,
                &quot;page&quot;: 1,
                &quot;active&quot;: true
            },
            {
                &quot;url&quot;: null,
                &quot;label&quot;: &quot;Next &amp;raquo;&quot;,
                &quot;page&quot;: null,
                &quot;active&quot;: false
            }
        ],
        &quot;path&quot;: &quot;https://api.wezone.app/api/ad-attribute-values&quot;,
        &quot;per_page&quot;: 25,
        &quot;to&quot;: null,
        &quot;total&quot;: 0
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ad-attribute-values" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ad-attribute-values"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ad-attribute-values"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-ad-attribute-values" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ad-attribute-values">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-ad-attribute-values" data-method="GET"
      data-path="api/ad-attribute-values"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ad-attribute-values', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ad-attribute-values"
                    onclick="tryItOut('GETapi-ad-attribute-values');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ad-attribute-values"
                    onclick="cancelTryOut('GETapi-ad-attribute-values');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ad-attribute-values"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ad-attribute-values</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-ad-attribute-values"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-ad-attribute-values"
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
                              name="Accept"                data-endpoint="GETapi-ad-attribute-values"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>definition_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="definition_id"                data-endpoint="GETapi-ad-attribute-values"
               value="12"
               data-component="query">
    <br>
<p>Filter by attribute definition. Example: <code>12</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>advertisable_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="advertisable_type"                data-endpoint="GETapi-ad-attribute-values"
               value="Modules\\Ad\\Models\\AdCar"
               data-component="query">
    <br>
<p>Filter by advertisable class name. Example: <code>Modules\\Ad\\Models\\AdCar</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>advertisable_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="advertisable_id"                data-endpoint="GETapi-ad-attribute-values"
               value="34"
               data-component="query">
    <br>
<p>Filter by advertisable identifier. Example: <code>34</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-ad-attribute-values"
               value="25"
               data-component="query">
    <br>
<p>Number of results per page, up to 200. Example: <code>25</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>without_pagination</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="GETapi-ad-attribute-values" style="display: none">
            <input type="radio" name="without_pagination"
                   value="1"
                   data-endpoint="GETapi-ad-attribute-values"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-ad-attribute-values" style="display: none">
            <input type="radio" name="without_pagination"
                   value="0"
                   data-endpoint="GETapi-ad-attribute-values"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Set to true to return all values without pagination. Example: <code>false</code></p>
            </div>
                </form>

                    <h2 id="ad-attribute-values-POSTapi-ad-attribute-values">Create an attribute value</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-ad-attribute-values">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/ad-attribute-values" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"definition_id\": 4,
    \"advertisable_type\": \"Modules\\\\Ad\\\\Models\\\\AdCar\",
    \"advertisable_id\": 15,
    \"value_string\": \"Automatic\",
    \"value_integer\": 5,
    \"value_decimal\": 1.6,
    \"value_boolean\": false,
    \"value_json\": {
        \"features\": [
            \"sunroof\",
            \"heated seats\"
        ]
    },
    \"normalized_value\": \"1.6\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ad-attribute-values"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "definition_id": 4,
    "advertisable_type": "Modules\\Ad\\Models\\AdCar",
    "advertisable_id": 15,
    "value_string": "Automatic",
    "value_integer": 5,
    "value_decimal": 1.6,
    "value_boolean": false,
    "value_json": {
        "features": [
            "sunroof",
            "heated seats"
        ]
    },
    "normalized_value": "1.6"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ad-attribute-values">
</span>
<span id="execution-results-POSTapi-ad-attribute-values" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ad-attribute-values"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ad-attribute-values"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ad-attribute-values" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ad-attribute-values">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ad-attribute-values" data-method="POST"
      data-path="api/ad-attribute-values"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ad-attribute-values', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ad-attribute-values"
                    onclick="tryItOut('POSTapi-ad-attribute-values');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ad-attribute-values"
                    onclick="cancelTryOut('POSTapi-ad-attribute-values');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ad-attribute-values"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ad-attribute-values</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ad-attribute-values"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ad-attribute-values"
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
                              name="Accept"                data-endpoint="POSTapi-ad-attribute-values"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>definition_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="definition_id"                data-endpoint="POSTapi-ad-attribute-values"
               value="4"
               data-component="body">
    <br>
<p>Identifier of the attribute definition being populated. The <code>id</code> of an existing record in the ad_attribute_definitions table. Example: <code>4</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>advertisable_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="advertisable_type"                data-endpoint="POSTapi-ad-attribute-values"
               value="Modules\Ad\Models\AdCar"
               data-component="body">
    <br>
<p>Fully qualified class name of the advertisable subtype. Example: <code>Modules\Ad\Models\AdCar</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>Modules\Ad\Models\AdCar</code></li> <li><code>Modules\Ad\Models\AdRealEstate</code></li> <li><code>Modules\Ad\Models\AdJob</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>advertisable_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="advertisable_id"                data-endpoint="POSTapi-ad-attribute-values"
               value="15"
               data-component="body">
    <br>
<p>Identifier of the advertisable record. Example: <code>15</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>value_string</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="value_string"                data-endpoint="POSTapi-ad-attribute-values"
               value="Automatic"
               data-component="body">
    <br>
<p>String value when the definition expects textual data. Example: <code>Automatic</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>value_integer</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="value_integer"                data-endpoint="POSTapi-ad-attribute-values"
               value="5"
               data-component="body">
    <br>
<p>Integer value when the definition expects whole numbers. Example: <code>5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>value_decimal</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="value_decimal"                data-endpoint="POSTapi-ad-attribute-values"
               value="1.6"
               data-component="body">
    <br>
<p>Decimal value when the definition expects numeric data. Example: <code>1.6</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>value_boolean</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-ad-attribute-values" style="display: none">
            <input type="radio" name="value_boolean"
                   value="true"
                   data-endpoint="POSTapi-ad-attribute-values"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-ad-attribute-values" style="display: none">
            <input type="radio" name="value_boolean"
                   value="false"
                   data-endpoint="POSTapi-ad-attribute-values"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Boolean value when the definition expects true or false. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>value_json</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="value_json"                data-endpoint="POSTapi-ad-attribute-values"
               value=""
               data-component="body">
    <br>
<p>Structured data payload for JSON definitions.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>normalized_value</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="normalized_value"                data-endpoint="POSTapi-ad-attribute-values"
               value="1.6"
               data-component="body">
    <br>
<p>Precomputed normalized representation used for search. Must not be greater than 255 characters. Example: <code>1.6</code></p>
        </div>
        </form>

                    <h2 id="ad-attribute-values-GETapi-ad-attribute-values--ad_attribute_value_id-">Show an attribute value</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-ad-attribute-values--ad_attribute_value_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/ad-attribute-values/architecto" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ad-attribute-values/architecto"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ad-attribute-values--ad_attribute_value_id-">
            <blockquote>
            <p>Example response (404):</p>
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
    &quot;message&quot;: &quot;No query results for model [Modules\\Ad\\Models\\AdAttributeValue] architecto&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ad-attribute-values--ad_attribute_value_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ad-attribute-values--ad_attribute_value_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ad-attribute-values--ad_attribute_value_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-ad-attribute-values--ad_attribute_value_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ad-attribute-values--ad_attribute_value_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-ad-attribute-values--ad_attribute_value_id-" data-method="GET"
      data-path="api/ad-attribute-values/{ad_attribute_value_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ad-attribute-values--ad_attribute_value_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ad-attribute-values--ad_attribute_value_id-"
                    onclick="tryItOut('GETapi-ad-attribute-values--ad_attribute_value_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ad-attribute-values--ad_attribute_value_id-"
                    onclick="cancelTryOut('GETapi-ad-attribute-values--ad_attribute_value_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ad-attribute-values--ad_attribute_value_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ad-attribute-values/{ad_attribute_value_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-ad-attribute-values--ad_attribute_value_id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-ad-attribute-values--ad_attribute_value_id-"
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
                              name="Accept"                data-endpoint="GETapi-ad-attribute-values--ad_attribute_value_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ad_attribute_value_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ad_attribute_value_id"                data-endpoint="GETapi-ad-attribute-values--ad_attribute_value_id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ad attribute value. Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="ad-attribute-values-POSTapi-ad-attribute-values--ad_attribute_value_id--update">Update an attribute value</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-ad-attribute-values--ad_attribute_value_id--update">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/ad-attribute-values/architecto/update" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"definition_id\": 4,
    \"advertisable_type\": \"Modules\\\\Ad\\\\Models\\\\AdCar\",
    \"advertisable_id\": 15,
    \"value_string\": \"Automatic\",
    \"value_integer\": 5,
    \"value_decimal\": 1.6,
    \"value_boolean\": false,
    \"value_json\": {
        \"features\": [
            \"sunroof\",
            \"heated seats\"
        ]
    },
    \"normalized_value\": \"1.6\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ad-attribute-values/architecto/update"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "definition_id": 4,
    "advertisable_type": "Modules\\Ad\\Models\\AdCar",
    "advertisable_id": 15,
    "value_string": "Automatic",
    "value_integer": 5,
    "value_decimal": 1.6,
    "value_boolean": false,
    "value_json": {
        "features": [
            "sunroof",
            "heated seats"
        ]
    },
    "normalized_value": "1.6"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ad-attribute-values--ad_attribute_value_id--update">
</span>
<span id="execution-results-POSTapi-ad-attribute-values--ad_attribute_value_id--update" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ad-attribute-values--ad_attribute_value_id--update"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ad-attribute-values--ad_attribute_value_id--update"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ad-attribute-values--ad_attribute_value_id--update" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ad-attribute-values--ad_attribute_value_id--update">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ad-attribute-values--ad_attribute_value_id--update" data-method="POST"
      data-path="api/ad-attribute-values/{ad_attribute_value_id}/update"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ad-attribute-values--ad_attribute_value_id--update', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ad-attribute-values--ad_attribute_value_id--update"
                    onclick="tryItOut('POSTapi-ad-attribute-values--ad_attribute_value_id--update');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ad-attribute-values--ad_attribute_value_id--update"
                    onclick="cancelTryOut('POSTapi-ad-attribute-values--ad_attribute_value_id--update');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ad-attribute-values--ad_attribute_value_id--update"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ad-attribute-values/{ad_attribute_value_id}/update</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ad-attribute-values--ad_attribute_value_id--update"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ad-attribute-values--ad_attribute_value_id--update"
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
                              name="Accept"                data-endpoint="POSTapi-ad-attribute-values--ad_attribute_value_id--update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ad_attribute_value_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ad_attribute_value_id"                data-endpoint="POSTapi-ad-attribute-values--ad_attribute_value_id--update"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ad attribute value. Example: <code>architecto</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>definition_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="definition_id"                data-endpoint="POSTapi-ad-attribute-values--ad_attribute_value_id--update"
               value="4"
               data-component="body">
    <br>
<p>Identifier of the attribute definition being populated. The <code>id</code> of an existing record in the ad_attribute_definitions table. Example: <code>4</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>advertisable_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="advertisable_type"                data-endpoint="POSTapi-ad-attribute-values--ad_attribute_value_id--update"
               value="Modules\Ad\Models\AdCar"
               data-component="body">
    <br>
<p>Fully qualified class name of the advertisable subtype. Example: <code>Modules\Ad\Models\AdCar</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>Modules\Ad\Models\AdCar</code></li> <li><code>Modules\Ad\Models\AdRealEstate</code></li> <li><code>Modules\Ad\Models\AdJob</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>advertisable_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="advertisable_id"                data-endpoint="POSTapi-ad-attribute-values--ad_attribute_value_id--update"
               value="15"
               data-component="body">
    <br>
<p>Identifier of the advertisable record. Example: <code>15</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>value_string</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="value_string"                data-endpoint="POSTapi-ad-attribute-values--ad_attribute_value_id--update"
               value="Automatic"
               data-component="body">
    <br>
<p>String value when the definition expects textual data. Example: <code>Automatic</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>value_integer</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="value_integer"                data-endpoint="POSTapi-ad-attribute-values--ad_attribute_value_id--update"
               value="5"
               data-component="body">
    <br>
<p>Integer value when the definition expects whole numbers. Example: <code>5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>value_decimal</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="value_decimal"                data-endpoint="POSTapi-ad-attribute-values--ad_attribute_value_id--update"
               value="1.6"
               data-component="body">
    <br>
<p>Decimal value when the definition expects numeric data. Example: <code>1.6</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>value_boolean</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-ad-attribute-values--ad_attribute_value_id--update" style="display: none">
            <input type="radio" name="value_boolean"
                   value="true"
                   data-endpoint="POSTapi-ad-attribute-values--ad_attribute_value_id--update"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-ad-attribute-values--ad_attribute_value_id--update" style="display: none">
            <input type="radio" name="value_boolean"
                   value="false"
                   data-endpoint="POSTapi-ad-attribute-values--ad_attribute_value_id--update"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Boolean value when the definition expects true or false. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>value_json</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="value_json"                data-endpoint="POSTapi-ad-attribute-values--ad_attribute_value_id--update"
               value=""
               data-component="body">
    <br>
<p>Structured data payload for JSON definitions.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>normalized_value</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="normalized_value"                data-endpoint="POSTapi-ad-attribute-values--ad_attribute_value_id--update"
               value="1.6"
               data-component="body">
    <br>
<p>Precomputed normalized representation used for search. Must not be greater than 255 characters. Example: <code>1.6</code></p>
        </div>
        </form>

                    <h2 id="ad-attribute-values-POSTapi-ad-attribute-values--ad_attribute_value_id--delete">Delete an attribute value</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-ad-attribute-values--ad_attribute_value_id--delete">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/ad-attribute-values/architecto/delete" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ad-attribute-values/architecto/delete"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ad-attribute-values--ad_attribute_value_id--delete">
</span>
<span id="execution-results-POSTapi-ad-attribute-values--ad_attribute_value_id--delete" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ad-attribute-values--ad_attribute_value_id--delete"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ad-attribute-values--ad_attribute_value_id--delete"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ad-attribute-values--ad_attribute_value_id--delete" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ad-attribute-values--ad_attribute_value_id--delete">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ad-attribute-values--ad_attribute_value_id--delete" data-method="POST"
      data-path="api/ad-attribute-values/{ad_attribute_value_id}/delete"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ad-attribute-values--ad_attribute_value_id--delete', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ad-attribute-values--ad_attribute_value_id--delete"
                    onclick="tryItOut('POSTapi-ad-attribute-values--ad_attribute_value_id--delete');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ad-attribute-values--ad_attribute_value_id--delete"
                    onclick="cancelTryOut('POSTapi-ad-attribute-values--ad_attribute_value_id--delete');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ad-attribute-values--ad_attribute_value_id--delete"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ad-attribute-values/{ad_attribute_value_id}/delete</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ad-attribute-values--ad_attribute_value_id--delete"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ad-attribute-values--ad_attribute_value_id--delete"
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
                              name="Accept"                data-endpoint="POSTapi-ad-attribute-values--ad_attribute_value_id--delete"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ad_attribute_value_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ad_attribute_value_id"                data-endpoint="POSTapi-ad-attribute-values--ad_attribute_value_id--delete"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ad attribute value. Example: <code>architecto</code></p>
            </div>
                    </form>

                <h1 id="ad-categories">Ad Categories</h1>

    <p>Fetch categories with optional filtering by parent, activation state, or search term.</p>

                                <h2 id="ad-categories-GETapi-ad-categories">List ad categories</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-ad-categories">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/ad-categories?parent_id=1&amp;only_active=1&amp;search=vehicles&amp;per_page=50&amp;without_pagination=" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ad-categories"
);

const params = {
    "parent_id": "1",
    "only_active": "1",
    "search": "vehicles",
    "per_page": "50",
    "without_pagination": "0",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ad-categories">
            <blockquote>
            <p>Example response (200):</p>
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
    &quot;data&quot;: [],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;https://api.wezone.app/api/ad-categories?parent_id=1&amp;only_active=1&amp;search=vehicles&amp;per_page=50&amp;without_pagination=0&amp;page=1&quot;,
        &quot;last&quot;: &quot;https://api.wezone.app/api/ad-categories?parent_id=1&amp;only_active=1&amp;search=vehicles&amp;per_page=50&amp;without_pagination=0&amp;page=1&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;from&quot;: null,
        &quot;last_page&quot;: 1,
        &quot;links&quot;: [
            {
                &quot;url&quot;: null,
                &quot;label&quot;: &quot;&amp;laquo; Previous&quot;,
                &quot;page&quot;: null,
                &quot;active&quot;: false
            },
            {
                &quot;url&quot;: &quot;https://api.wezone.app/api/ad-categories?parent_id=1&amp;only_active=1&amp;search=vehicles&amp;per_page=50&amp;without_pagination=0&amp;page=1&quot;,
                &quot;label&quot;: &quot;1&quot;,
                &quot;page&quot;: 1,
                &quot;active&quot;: true
            },
            {
                &quot;url&quot;: null,
                &quot;label&quot;: &quot;Next &amp;raquo;&quot;,
                &quot;page&quot;: null,
                &quot;active&quot;: false
            }
        ],
        &quot;path&quot;: &quot;https://api.wezone.app/api/ad-categories&quot;,
        &quot;per_page&quot;: 50,
        &quot;to&quot;: null,
        &quot;total&quot;: 0
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ad-categories" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ad-categories"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ad-categories"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-ad-categories" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ad-categories">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-ad-categories" data-method="GET"
      data-path="api/ad-categories"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ad-categories', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ad-categories"
                    onclick="tryItOut('GETapi-ad-categories');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ad-categories"
                    onclick="cancelTryOut('GETapi-ad-categories');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ad-categories"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ad-categories</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-ad-categories"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-ad-categories"
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
                              name="Accept"                data-endpoint="GETapi-ad-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>parent_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="parent_id"                data-endpoint="GETapi-ad-categories"
               value="1"
               data-component="query">
    <br>
<p>Filter categories by parent identifier. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>only_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="GETapi-ad-categories" style="display: none">
            <input type="radio" name="only_active"
                   value="1"
                   data-endpoint="GETapi-ad-categories"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-ad-categories" style="display: none">
            <input type="radio" name="only_active"
                   value="0"
                   data-endpoint="GETapi-ad-categories"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Return only active categories. Example: <code>true</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-ad-categories"
               value="vehicles"
               data-component="query">
    <br>
<p>Search by category name or slug. Example: <code>vehicles</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-ad-categories"
               value="50"
               data-component="query">
    <br>
<p>Number of results per page, up to 200. Example: <code>50</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>without_pagination</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="GETapi-ad-categories" style="display: none">
            <input type="radio" name="without_pagination"
                   value="1"
                   data-endpoint="GETapi-ad-categories"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-ad-categories" style="display: none">
            <input type="radio" name="without_pagination"
                   value="0"
                   data-endpoint="GETapi-ad-categories"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Set to true to receive all categories without pagination. Example: <code>false</code></p>
            </div>
                </form>

                    <h2 id="ad-categories-POSTapi-ad-categories">Create a category</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-ad-categories">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/ad-categories" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"parent_id\": 1,
    \"slug\": \"vehicles\",
    \"name\": \"Vehicles\",
    \"name_localized\": {
        \"fa\": \"وسایل نقلیه\"
    },
    \"is_active\": false,
    \"sort_order\": 5,
    \"filters_schema\": {
        \"color\": {
            \"type\": \"enum\",
            \"options\": [
                \"red\",
                \"blue\"
            ]
        }
    }
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ad-categories"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "parent_id": 1,
    "slug": "vehicles",
    "name": "Vehicles",
    "name_localized": {
        "fa": "وسایل نقلیه"
    },
    "is_active": false,
    "sort_order": 5,
    "filters_schema": {
        "color": {
            "type": "enum",
            "options": [
                "red",
                "blue"
            ]
        }
    }
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ad-categories">
</span>
<span id="execution-results-POSTapi-ad-categories" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ad-categories"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ad-categories"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ad-categories" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ad-categories">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ad-categories" data-method="POST"
      data-path="api/ad-categories"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ad-categories', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ad-categories"
                    onclick="tryItOut('POSTapi-ad-categories');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ad-categories"
                    onclick="cancelTryOut('POSTapi-ad-categories');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ad-categories"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ad-categories</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ad-categories"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ad-categories"
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
                              name="Accept"                data-endpoint="POSTapi-ad-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>parent_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="parent_id"                data-endpoint="POSTapi-ad-categories"
               value="1"
               data-component="body">
    <br>
<p>Identifier of the parent category. The <code>id</code> of an existing record in the ad_categories table. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="POSTapi-ad-categories"
               value="vehicles"
               data-component="body">
    <br>
<p>Unique slug for the category. Must contain only letters, numbers, dashes and underscores. Must not be greater than 255 characters. Example: <code>vehicles</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-ad-categories"
               value="Vehicles"
               data-component="body">
    <br>
<p>Display name of the category. Must not be greater than 255 characters. Example: <code>Vehicles</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name_localized</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="name_localized"                data-endpoint="POSTapi-ad-categories"
               value=""
               data-component="body">
    <br>
<p>Localized translations for the category name.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-ad-categories" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="POSTapi-ad-categories"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-ad-categories" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="POSTapi-ad-categories"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Toggle to activate or deactivate the category. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>sort_order</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="sort_order"                data-endpoint="POSTapi-ad-categories"
               value="5"
               data-component="body">
    <br>
<p>Custom ordering index. Example: <code>5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>filters_schema</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="filters_schema"                data-endpoint="POSTapi-ad-categories"
               value=""
               data-component="body">
    <br>
<p>JSON schema describing available filters.</p>
        </div>
        </form>

                    <h2 id="ad-categories-GETapi-ad-categories--ad_category_id-">Show a category</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-ad-categories--ad_category_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/ad-categories/architecto" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ad-categories/architecto"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ad-categories--ad_category_id-">
            <blockquote>
            <p>Example response (404):</p>
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
    &quot;message&quot;: &quot;No query results for model [Modules\\Ad\\Models\\AdCategory] architecto&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ad-categories--ad_category_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ad-categories--ad_category_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ad-categories--ad_category_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-ad-categories--ad_category_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ad-categories--ad_category_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-ad-categories--ad_category_id-" data-method="GET"
      data-path="api/ad-categories/{ad_category_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ad-categories--ad_category_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ad-categories--ad_category_id-"
                    onclick="tryItOut('GETapi-ad-categories--ad_category_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ad-categories--ad_category_id-"
                    onclick="cancelTryOut('GETapi-ad-categories--ad_category_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ad-categories--ad_category_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ad-categories/{ad_category_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-ad-categories--ad_category_id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-ad-categories--ad_category_id-"
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
                              name="Accept"                data-endpoint="GETapi-ad-categories--ad_category_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ad_category_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ad_category_id"                data-endpoint="GETapi-ad-categories--ad_category_id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ad category. Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="ad-categories-POSTapi-ad-categories--ad_category_id--update">Update a category</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-ad-categories--ad_category_id--update">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/ad-categories/architecto/update" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"parent_id\": 1,
    \"slug\": \"vehicles\",
    \"name\": \"Vehicles\",
    \"name_localized\": {
        \"fa\": \"وسایل نقلیه\"
    },
    \"is_active\": false,
    \"sort_order\": 5,
    \"filters_schema\": {
        \"color\": {
            \"type\": \"enum\",
            \"options\": [
                \"red\",
                \"blue\"
            ]
        }
    }
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ad-categories/architecto/update"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "parent_id": 1,
    "slug": "vehicles",
    "name": "Vehicles",
    "name_localized": {
        "fa": "وسایل نقلیه"
    },
    "is_active": false,
    "sort_order": 5,
    "filters_schema": {
        "color": {
            "type": "enum",
            "options": [
                "red",
                "blue"
            ]
        }
    }
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ad-categories--ad_category_id--update">
</span>
<span id="execution-results-POSTapi-ad-categories--ad_category_id--update" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ad-categories--ad_category_id--update"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ad-categories--ad_category_id--update"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ad-categories--ad_category_id--update" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ad-categories--ad_category_id--update">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ad-categories--ad_category_id--update" data-method="POST"
      data-path="api/ad-categories/{ad_category_id}/update"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ad-categories--ad_category_id--update', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ad-categories--ad_category_id--update"
                    onclick="tryItOut('POSTapi-ad-categories--ad_category_id--update');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ad-categories--ad_category_id--update"
                    onclick="cancelTryOut('POSTapi-ad-categories--ad_category_id--update');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ad-categories--ad_category_id--update"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ad-categories/{ad_category_id}/update</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ad-categories--ad_category_id--update"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ad-categories--ad_category_id--update"
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
                              name="Accept"                data-endpoint="POSTapi-ad-categories--ad_category_id--update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ad_category_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ad_category_id"                data-endpoint="POSTapi-ad-categories--ad_category_id--update"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ad category. Example: <code>architecto</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>parent_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="parent_id"                data-endpoint="POSTapi-ad-categories--ad_category_id--update"
               value="1"
               data-component="body">
    <br>
<p>Identifier of the parent category. The <code>id</code> of an existing record in the ad_categories table. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="POSTapi-ad-categories--ad_category_id--update"
               value="vehicles"
               data-component="body">
    <br>
<p>Unique slug for the category. Must contain only letters, numbers, dashes and underscores. Must not be greater than 255 characters. Example: <code>vehicles</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-ad-categories--ad_category_id--update"
               value="Vehicles"
               data-component="body">
    <br>
<p>Display name of the category. Must not be greater than 255 characters. Example: <code>Vehicles</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name_localized</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="name_localized"                data-endpoint="POSTapi-ad-categories--ad_category_id--update"
               value=""
               data-component="body">
    <br>
<p>Localized translations for the category name.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-ad-categories--ad_category_id--update" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="POSTapi-ad-categories--ad_category_id--update"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-ad-categories--ad_category_id--update" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="POSTapi-ad-categories--ad_category_id--update"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Toggle to activate or deactivate the category. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>sort_order</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="sort_order"                data-endpoint="POSTapi-ad-categories--ad_category_id--update"
               value="5"
               data-component="body">
    <br>
<p>Custom ordering index. Example: <code>5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>filters_schema</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="filters_schema"                data-endpoint="POSTapi-ad-categories--ad_category_id--update"
               value=""
               data-component="body">
    <br>
<p>JSON schema describing available filters.</p>
        </div>
        </form>

                    <h2 id="ad-categories-POSTapi-ad-categories--ad_category_id--delete">Delete a category</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-ad-categories--ad_category_id--delete">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/ad-categories/architecto/delete" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ad-categories/architecto/delete"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ad-categories--ad_category_id--delete">
</span>
<span id="execution-results-POSTapi-ad-categories--ad_category_id--delete" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ad-categories--ad_category_id--delete"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ad-categories--ad_category_id--delete"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ad-categories--ad_category_id--delete" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ad-categories--ad_category_id--delete">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ad-categories--ad_category_id--delete" data-method="POST"
      data-path="api/ad-categories/{ad_category_id}/delete"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ad-categories--ad_category_id--delete', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ad-categories--ad_category_id--delete"
                    onclick="tryItOut('POSTapi-ad-categories--ad_category_id--delete');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ad-categories--ad_category_id--delete"
                    onclick="cancelTryOut('POSTapi-ad-categories--ad_category_id--delete');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ad-categories--ad_category_id--delete"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ad-categories/{ad_category_id}/delete</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ad-categories--ad_category_id--delete"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ad-categories--ad_category_id--delete"
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
                              name="Accept"                data-endpoint="POSTapi-ad-categories--ad_category_id--delete"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ad_category_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ad_category_id"                data-endpoint="POSTapi-ad-categories--ad_category_id--delete"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ad category. Example: <code>architecto</code></p>
            </div>
                    </form>

                <h1 id="ads">Ads</h1>

    <p>Retrieve a filtered or paginated list of ads.</p>

                                <h2 id="ads-GETapi-ads">List ads</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-ads">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/ads?status=published&amp;user_id=12&amp;category_id=5&amp;search=sedan&amp;only_published=1&amp;per_page=25&amp;without_pagination=" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ads"
);

const params = {
    "status": "published",
    "user_id": "12",
    "category_id": "5",
    "search": "sedan",
    "only_published": "1",
    "per_page": "25",
    "without_pagination": "0",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ads">
            <blockquote>
            <p>Example response (500):</p>
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
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ads" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ads"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ads"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-ads" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ads">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-ads" data-method="GET"
      data-path="api/ads"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ads', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ads"
                    onclick="tryItOut('GETapi-ads');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ads"
                    onclick="cancelTryOut('GETapi-ads');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ads"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ads</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-ads"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-ads"
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
                              name="Accept"                data-endpoint="GETapi-ads"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="GETapi-ads"
               value="published"
               data-component="query">
    <br>
<p>Filter by lifecycle status. Example: <code>published</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="GETapi-ads"
               value="12"
               data-component="query">
    <br>
<p>Filter by owner user ID. Example: <code>12</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>category_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="category_id"                data-endpoint="GETapi-ads"
               value="5"
               data-component="query">
    <br>
<p>Limit to ads attached to the provided category. Example: <code>5</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-ads"
               value="sedan"
               data-component="query">
    <br>
<p>Search within title and description text. Example: <code>sedan</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>only_published</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="GETapi-ads" style="display: none">
            <input type="radio" name="only_published"
                   value="1"
                   data-endpoint="GETapi-ads"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-ads" style="display: none">
            <input type="radio" name="only_published"
                   value="0"
                   data-endpoint="GETapi-ads"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Return only published records when true. Example: <code>true</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-ads"
               value="25"
               data-component="query">
    <br>
<p>Number of results per page, up to 100. Example: <code>25</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>without_pagination</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="GETapi-ads" style="display: none">
            <input type="radio" name="without_pagination"
                   value="1"
                   data-endpoint="GETapi-ads"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-ads" style="display: none">
            <input type="radio" name="without_pagination"
                   value="0"
                   data-endpoint="GETapi-ads"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Set to true to return all records without pagination. Example: <code>false</code></p>
            </div>
                </form>

                    <h2 id="ads-POSTapi-ads">Create an ad</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-ads">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/ads" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"user_id\": 42,
    \"advertisable_type\": \"Modules\\\\Ad\\\\Models\\\\AdCar\",
    \"advertisable_id\": 10,
    \"slug\": \"peugeot-206-2024\",
    \"title\": \"Peugeot 206 2024\",
    \"subtitle\": \"Full options, low mileage\",
    \"description\": \"One owner, regularly serviced, ready to drive.\",
    \"status\": \"draft\",
    \"published_at\": \"2024-05-01T08:00:00Z\",
    \"expires_at\": \"2024-06-01T08:00:00Z\",
    \"price_amount\": 450000000,
    \"price_currency\": \"IRR\",
    \"is_negotiable\": false,
    \"is_exchangeable\": false,
    \"city_id\": 3,
    \"province_id\": 1,
    \"latitude\": 35.6892,
    \"longitude\": 51.389,
    \"contact_channel\": {
        \"phone\": \"123456789\"
    },
    \"view_count\": 0,
    \"share_count\": 0,
    \"favorite_count\": 0,
    \"featured_until\": \"2024-05-15T08:00:00Z\",
    \"priority_score\": 12.5,
    \"categories\": [
        {
            \"id\": 7,
            \"is_primary\": true,
            \"assigned_by\": 42
        }
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ads"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "user_id": 42,
    "advertisable_type": "Modules\\Ad\\Models\\AdCar",
    "advertisable_id": 10,
    "slug": "peugeot-206-2024",
    "title": "Peugeot 206 2024",
    "subtitle": "Full options, low mileage",
    "description": "One owner, regularly serviced, ready to drive.",
    "status": "draft",
    "published_at": "2024-05-01T08:00:00Z",
    "expires_at": "2024-06-01T08:00:00Z",
    "price_amount": 450000000,
    "price_currency": "IRR",
    "is_negotiable": false,
    "is_exchangeable": false,
    "city_id": 3,
    "province_id": 1,
    "latitude": 35.6892,
    "longitude": 51.389,
    "contact_channel": {
        "phone": "123456789"
    },
    "view_count": 0,
    "share_count": 0,
    "favorite_count": 0,
    "featured_until": "2024-05-15T08:00:00Z",
    "priority_score": 12.5,
    "categories": [
        {
            "id": 7,
            "is_primary": true,
            "assigned_by": 42
        }
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ads">
</span>
<span id="execution-results-POSTapi-ads" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ads"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ads"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ads" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ads">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ads" data-method="POST"
      data-path="api/ads"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ads', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ads"
                    onclick="tryItOut('POSTapi-ads');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ads"
                    onclick="cancelTryOut('POSTapi-ads');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ads"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ads</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ads"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ads"
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
                              name="Accept"                data-endpoint="POSTapi-ads"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="user_id"                data-endpoint="POSTapi-ads"
               value="42"
               data-component="body">
    <br>
<p>Identifier of the ad owner. The <code>id</code> of an existing record in the users table. Example: <code>42</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>advertisable_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="advertisable_type"                data-endpoint="POSTapi-ads"
               value="Modules\Ad\Models\AdCar"
               data-component="body">
    <br>
<p>Fully qualified class name of the advertisable subtype. Example: <code>Modules\Ad\Models\AdCar</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>Modules\Ad\Models\AdCar</code></li> <li><code>Modules\Ad\Models\AdRealEstate</code></li> <li><code>Modules\Ad\Models\AdJob</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>advertisable_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="advertisable_id"                data-endpoint="POSTapi-ads"
               value="10"
               data-component="body">
    <br>
<p>Identifier of the advertisable record. Example: <code>10</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="POSTapi-ads"
               value="peugeot-206-2024"
               data-component="body">
    <br>
<p>Unique slug for the ad. Must contain only letters, numbers, dashes and underscores. Must not be greater than 255 characters. Example: <code>peugeot-206-2024</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>title</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="title"                data-endpoint="POSTapi-ads"
               value="Peugeot 206 2024"
               data-component="body">
    <br>
<p>Headline displayed for the ad. Must not be greater than 255 characters. Example: <code>Peugeot 206 2024</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>subtitle</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="subtitle"                data-endpoint="POSTapi-ads"
               value="Full options, low mileage"
               data-component="body">
    <br>
<p>Optional subtitle or tagline. Must not be greater than 255 characters. Example: <code>Full options, low mileage</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-ads"
               value="One owner, regularly serviced, ready to drive."
               data-component="body">
    <br>
<p>Rich description of the listing. Example: <code>One owner, regularly serviced, ready to drive.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="POSTapi-ads"
               value="draft"
               data-component="body">
    <br>
<p>Lifecycle status for moderation. Example: <code>draft</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>draft</code></li> <li><code>pending_review</code></li> <li><code>published</code></li> <li><code>rejected</code></li> <li><code>archived</code></li> <li><code>expired</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>published_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="published_at"                data-endpoint="POSTapi-ads"
               value="2024-05-01T08:00:00Z"
               data-component="body">
    <br>
<p>Publication datetime in ISO 8601 format. Must be a valid date. Example: <code>2024-05-01T08:00:00Z</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>expires_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="expires_at"                data-endpoint="POSTapi-ads"
               value="2024-06-01T08:00:00Z"
               data-component="body">
    <br>
<p>Optional expiration datetime in ISO 8601 format. Must be a valid date. Must be a date after or equal to <code>published_at</code>. Example: <code>2024-06-01T08:00:00Z</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>price_amount</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="price_amount"                data-endpoint="POSTapi-ads"
               value="450000000"
               data-component="body">
    <br>
<p>Price stored in the smallest currency unit. Example: <code>450000000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>price_currency</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="price_currency"                data-endpoint="POSTapi-ads"
               value="IRR"
               data-component="body">
    <br>
<p>Three-letter ISO currency code. Must be 3 characters. Example: <code>IRR</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_negotiable</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-ads" style="display: none">
            <input type="radio" name="is_negotiable"
                   value="true"
                   data-endpoint="POSTapi-ads"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-ads" style="display: none">
            <input type="radio" name="is_negotiable"
                   value="false"
                   data-endpoint="POSTapi-ads"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Indicates if the price can be negotiated. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_exchangeable</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-ads" style="display: none">
            <input type="radio" name="is_exchangeable"
                   value="true"
                   data-endpoint="POSTapi-ads"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-ads" style="display: none">
            <input type="radio" name="is_exchangeable"
                   value="false"
                   data-endpoint="POSTapi-ads"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Indicates if swaps are accepted. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>city_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="city_id"                data-endpoint="POSTapi-ads"
               value="3"
               data-component="body">
    <br>
<p>City identifier for the ad location. The <code>id</code> of an existing record in the cities table. Example: <code>3</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>province_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="province_id"                data-endpoint="POSTapi-ads"
               value="1"
               data-component="body">
    <br>
<p>Province identifier for the ad location. The <code>id</code> of an existing record in the provinces table. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>latitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="latitude"                data-endpoint="POSTapi-ads"
               value="35.6892"
               data-component="body">
    <br>
<p>Latitude coordinate of the listing. Must be between -90 and 90. Example: <code>35.6892</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>longitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="longitude"                data-endpoint="POSTapi-ads"
               value="51.389"
               data-component="body">
    <br>
<p>Longitude coordinate of the listing. Must be between -180 and 180. Example: <code>51.389</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>contact_channel</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="contact_channel"                data-endpoint="POSTapi-ads"
               value=""
               data-component="body">
    <br>
<p>Contact details such as phone or messenger usernames.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>view_count</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="view_count"                data-endpoint="POSTapi-ads"
               value="0"
               data-component="body">
    <br>
<p>Pre-set view counter value, typically managed internally. Must be at least 0. Example: <code>0</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>share_count</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="share_count"                data-endpoint="POSTapi-ads"
               value="0"
               data-component="body">
    <br>
<p>Pre-set share counter value, typically managed internally. Must be at least 0. Example: <code>0</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>favorite_count</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="favorite_count"                data-endpoint="POSTapi-ads"
               value="0"
               data-component="body">
    <br>
<p>Pre-set favorite counter value, typically managed internally. Must be at least 0. Example: <code>0</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>featured_until</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="featured_until"                data-endpoint="POSTapi-ads"
               value="2024-05-15T08:00:00Z"
               data-component="body">
    <br>
<p>Datetime until which the ad remains featured. Must be a valid date. Example: <code>2024-05-15T08:00:00Z</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>priority_score</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="priority_score"                data-endpoint="POSTapi-ads"
               value="12.5"
               data-component="body">
    <br>
<p>Numeric score affecting ordering. Example: <code>12.5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>categories</code></b>&nbsp;&nbsp;
<small>object[]</small>&nbsp;
<i>optional</i> &nbsp;
<br>
<p>Array of category assignments.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="categories.0.id"                data-endpoint="POSTapi-ads"
               value="16"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the ad_categories table. Example: <code>16</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>is_primary</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-ads" style="display: none">
            <input type="radio" name="categories.0.is_primary"
                   value="true"
                   data-endpoint="POSTapi-ads"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-ads" style="display: none">
            <input type="radio" name="categories.0.is_primary"
                   value="false"
                   data-endpoint="POSTapi-ads"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>true</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>assigned_by</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="categories.0.assigned_by"                data-endpoint="POSTapi-ads"
               value="16"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the users table. Example: <code>16</code></p>
                    </div>
                                    </details>
        </div>
        </form>

                    <h2 id="ads-GETapi-ads--ad_id-">Show ad details</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-ads--ad_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/ads/16" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ads/16"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ads--ad_id-">
            <blockquote>
            <p>Example response (404):</p>
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
    &quot;message&quot;: &quot;No query results for model [Modules\\Ad\\Models\\Ad] 16&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ads--ad_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ads--ad_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ads--ad_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-ads--ad_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ads--ad_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-ads--ad_id-" data-method="GET"
      data-path="api/ads/{ad_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ads--ad_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ads--ad_id-"
                    onclick="tryItOut('GETapi-ads--ad_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ads--ad_id-"
                    onclick="cancelTryOut('GETapi-ads--ad_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ads--ad_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ads/{ad_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-ads--ad_id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-ads--ad_id-"
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
                              name="Accept"                data-endpoint="GETapi-ads--ad_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ad_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="ad_id"                data-endpoint="GETapi-ads--ad_id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the ad. Example: <code>16</code></p>
            </div>
                    </form>

                    <h2 id="ads-POSTapi-ads--ad_id--update">Update an ad</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-ads--ad_id--update">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/ads/16/update" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"user_id\": 42,
    \"advertisable_type\": \"Modules\\\\Ad\\\\Models\\\\AdCar\",
    \"advertisable_id\": 10,
    \"slug\": \"peugeot-206-2024\",
    \"title\": \"Peugeot 206 2024\",
    \"subtitle\": \"Full options, low mileage\",
    \"description\": \"One owner, regularly serviced, ready to drive.\",
    \"status\": \"published\",
    \"published_at\": \"2024-05-01T08:00:00Z\",
    \"expires_at\": \"2024-06-01T08:00:00Z\",
    \"price_amount\": 450000000,
    \"price_currency\": \"IRR\",
    \"is_negotiable\": false,
    \"is_exchangeable\": false,
    \"city_id\": 3,
    \"province_id\": 1,
    \"latitude\": 35.6892,
    \"longitude\": 51.389,
    \"contact_channel\": {
        \"phone\": \"123456789\",
        \"telegram\": \"@majid\"
    },
    \"view_count\": 100,
    \"share_count\": 10,
    \"favorite_count\": 25,
    \"featured_until\": \"2024-05-15T08:00:00Z\",
    \"priority_score\": 12.5,
    \"categories\": [
        {
            \"id\": 7,
            \"is_primary\": true,
            \"assigned_by\": 42
        },
        {
            \"id\": 12,
            \"is_primary\": false
        }
    ],
    \"status_note\": \"Approved by moderator\",
    \"status_metadata\": {
        \"moderator\": \"system\"
    }
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ads/16/update"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "user_id": 42,
    "advertisable_type": "Modules\\Ad\\Models\\AdCar",
    "advertisable_id": 10,
    "slug": "peugeot-206-2024",
    "title": "Peugeot 206 2024",
    "subtitle": "Full options, low mileage",
    "description": "One owner, regularly serviced, ready to drive.",
    "status": "published",
    "published_at": "2024-05-01T08:00:00Z",
    "expires_at": "2024-06-01T08:00:00Z",
    "price_amount": 450000000,
    "price_currency": "IRR",
    "is_negotiable": false,
    "is_exchangeable": false,
    "city_id": 3,
    "province_id": 1,
    "latitude": 35.6892,
    "longitude": 51.389,
    "contact_channel": {
        "phone": "123456789",
        "telegram": "@majid"
    },
    "view_count": 100,
    "share_count": 10,
    "favorite_count": 25,
    "featured_until": "2024-05-15T08:00:00Z",
    "priority_score": 12.5,
    "categories": [
        {
            "id": 7,
            "is_primary": true,
            "assigned_by": 42
        },
        {
            "id": 12,
            "is_primary": false
        }
    ],
    "status_note": "Approved by moderator",
    "status_metadata": {
        "moderator": "system"
    }
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ads--ad_id--update">
</span>
<span id="execution-results-POSTapi-ads--ad_id--update" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ads--ad_id--update"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ads--ad_id--update"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ads--ad_id--update" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ads--ad_id--update">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ads--ad_id--update" data-method="POST"
      data-path="api/ads/{ad_id}/update"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ads--ad_id--update', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ads--ad_id--update"
                    onclick="tryItOut('POSTapi-ads--ad_id--update');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ads--ad_id--update"
                    onclick="cancelTryOut('POSTapi-ads--ad_id--update');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ads--ad_id--update"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ads/{ad_id}/update</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ads--ad_id--update"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ads--ad_id--update"
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
                              name="Accept"                data-endpoint="POSTapi-ads--ad_id--update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ad_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="ad_id"                data-endpoint="POSTapi-ads--ad_id--update"
               value="16"
               data-component="url">
    <br>
<p>The ID of the ad. Example: <code>16</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="user_id"                data-endpoint="POSTapi-ads--ad_id--update"
               value="42"
               data-component="body">
    <br>
<p>Identifier of the ad owner. The <code>id</code> of an existing record in the users table. Example: <code>42</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>advertisable_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="advertisable_type"                data-endpoint="POSTapi-ads--ad_id--update"
               value="Modules\Ad\Models\AdCar"
               data-component="body">
    <br>
<p>Fully qualified class name of the advertisable subtype. Example: <code>Modules\Ad\Models\AdCar</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>Modules\Ad\Models\AdCar</code></li> <li><code>Modules\Ad\Models\AdRealEstate</code></li> <li><code>Modules\Ad\Models\AdJob</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>advertisable_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="advertisable_id"                data-endpoint="POSTapi-ads--ad_id--update"
               value="10"
               data-component="body">
    <br>
<p>Identifier of the advertisable record. Example: <code>10</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="POSTapi-ads--ad_id--update"
               value="peugeot-206-2024"
               data-component="body">
    <br>
<p>Unique slug for the ad. Must contain only letters, numbers, dashes and underscores. Must not be greater than 255 characters. Example: <code>peugeot-206-2024</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>title</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="title"                data-endpoint="POSTapi-ads--ad_id--update"
               value="Peugeot 206 2024"
               data-component="body">
    <br>
<p>Headline displayed for the ad. Must not be greater than 255 characters. Example: <code>Peugeot 206 2024</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>subtitle</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="subtitle"                data-endpoint="POSTapi-ads--ad_id--update"
               value="Full options, low mileage"
               data-component="body">
    <br>
<p>Optional subtitle or tagline. Must not be greater than 255 characters. Example: <code>Full options, low mileage</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-ads--ad_id--update"
               value="One owner, regularly serviced, ready to drive."
               data-component="body">
    <br>
<p>Rich description of the listing. Example: <code>One owner, regularly serviced, ready to drive.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="POSTapi-ads--ad_id--update"
               value="published"
               data-component="body">
    <br>
<p>Lifecycle status for moderation. Example: <code>published</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>draft</code></li> <li><code>pending_review</code></li> <li><code>published</code></li> <li><code>rejected</code></li> <li><code>archived</code></li> <li><code>expired</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>published_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="published_at"                data-endpoint="POSTapi-ads--ad_id--update"
               value="2024-05-01T08:00:00Z"
               data-component="body">
    <br>
<p>Publication datetime in ISO 8601 format. Must be a valid date. Example: <code>2024-05-01T08:00:00Z</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>expires_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="expires_at"                data-endpoint="POSTapi-ads--ad_id--update"
               value="2024-06-01T08:00:00Z"
               data-component="body">
    <br>
<p>Optional expiration datetime in ISO 8601 format. Must be a valid date. Must be a date after or equal to <code>published_at</code>. Example: <code>2024-06-01T08:00:00Z</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>price_amount</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="price_amount"                data-endpoint="POSTapi-ads--ad_id--update"
               value="450000000"
               data-component="body">
    <br>
<p>Price stored in the smallest currency unit. Example: <code>450000000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>price_currency</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="price_currency"                data-endpoint="POSTapi-ads--ad_id--update"
               value="IRR"
               data-component="body">
    <br>
<p>Three-letter ISO currency code. Must be 3 characters. Example: <code>IRR</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_negotiable</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-ads--ad_id--update" style="display: none">
            <input type="radio" name="is_negotiable"
                   value="true"
                   data-endpoint="POSTapi-ads--ad_id--update"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-ads--ad_id--update" style="display: none">
            <input type="radio" name="is_negotiable"
                   value="false"
                   data-endpoint="POSTapi-ads--ad_id--update"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Indicates if the price can be negotiated. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_exchangeable</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-ads--ad_id--update" style="display: none">
            <input type="radio" name="is_exchangeable"
                   value="true"
                   data-endpoint="POSTapi-ads--ad_id--update"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-ads--ad_id--update" style="display: none">
            <input type="radio" name="is_exchangeable"
                   value="false"
                   data-endpoint="POSTapi-ads--ad_id--update"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Indicates if swaps are accepted. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>city_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="city_id"                data-endpoint="POSTapi-ads--ad_id--update"
               value="3"
               data-component="body">
    <br>
<p>City identifier for the ad location. The <code>id</code> of an existing record in the cities table. Example: <code>3</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>province_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="province_id"                data-endpoint="POSTapi-ads--ad_id--update"
               value="1"
               data-component="body">
    <br>
<p>Province identifier for the ad location. The <code>id</code> of an existing record in the provinces table. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>latitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="latitude"                data-endpoint="POSTapi-ads--ad_id--update"
               value="35.6892"
               data-component="body">
    <br>
<p>Latitude coordinate of the listing. Must be between -90 and 90. Example: <code>35.6892</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>longitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="longitude"                data-endpoint="POSTapi-ads--ad_id--update"
               value="51.389"
               data-component="body">
    <br>
<p>Longitude coordinate of the listing. Must be between -180 and 180. Example: <code>51.389</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>contact_channel</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="contact_channel"                data-endpoint="POSTapi-ads--ad_id--update"
               value=""
               data-component="body">
    <br>
<p>Contact details such as phone or messenger usernames.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>view_count</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="view_count"                data-endpoint="POSTapi-ads--ad_id--update"
               value="100"
               data-component="body">
    <br>
<p>Pre-set view counter value, typically managed internally. Must be at least 0. Example: <code>100</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>share_count</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="share_count"                data-endpoint="POSTapi-ads--ad_id--update"
               value="10"
               data-component="body">
    <br>
<p>Pre-set share counter value, typically managed internally. Must be at least 0. Example: <code>10</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>favorite_count</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="favorite_count"                data-endpoint="POSTapi-ads--ad_id--update"
               value="25"
               data-component="body">
    <br>
<p>Pre-set favorite counter value, typically managed internally. Must be at least 0. Example: <code>25</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>featured_until</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="featured_until"                data-endpoint="POSTapi-ads--ad_id--update"
               value="2024-05-15T08:00:00Z"
               data-component="body">
    <br>
<p>Datetime until which the ad remains featured. Must be a valid date. Example: <code>2024-05-15T08:00:00Z</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>priority_score</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="priority_score"                data-endpoint="POSTapi-ads--ad_id--update"
               value="12.5"
               data-component="body">
    <br>
<p>Numeric score affecting ordering. Example: <code>12.5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>categories</code></b>&nbsp;&nbsp;
<small>object[]</small>&nbsp;
<i>optional</i> &nbsp;
<br>
<p>Array of category assignments.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="categories.0.id"                data-endpoint="POSTapi-ads--ad_id--update"
               value="16"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the ad_categories table. Example: <code>16</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>is_primary</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-ads--ad_id--update" style="display: none">
            <input type="radio" name="categories.0.is_primary"
                   value="true"
                   data-endpoint="POSTapi-ads--ad_id--update"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-ads--ad_id--update" style="display: none">
            <input type="radio" name="categories.0.is_primary"
                   value="false"
                   data-endpoint="POSTapi-ads--ad_id--update"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>false</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>assigned_by</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="categories.0.assigned_by"                data-endpoint="POSTapi-ads--ad_id--update"
               value="16"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the users table. Example: <code>16</code></p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status_note</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="status_note"                data-endpoint="POSTapi-ads--ad_id--update"
               value="Approved by moderator"
               data-component="body">
    <br>
<p>Optional note saved alongside status changes. Example: <code>Approved by moderator</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status_metadata</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="status_metadata"                data-endpoint="POSTapi-ads--ad_id--update"
               value=""
               data-component="body">
    <br>
<p>Structured metadata explaining the status change.</p>
        </div>
        </form>

                    <h2 id="ads-POSTapi-ads--ad_id--delete">Delete an ad</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-ads--ad_id--delete">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/ads/16/delete" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/ads/16/delete"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ads--ad_id--delete">
</span>
<span id="execution-results-POSTapi-ads--ad_id--delete" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ads--ad_id--delete"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ads--ad_id--delete"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-ads--ad_id--delete" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ads--ad_id--delete">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-ads--ad_id--delete" data-method="POST"
      data-path="api/ads/{ad_id}/delete"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ads--ad_id--delete', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ads--ad_id--delete"
                    onclick="tryItOut('POSTapi-ads--ad_id--delete');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ads--ad_id--delete"
                    onclick="cancelTryOut('POSTapi-ads--ad_id--delete');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ads--ad_id--delete"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ads/{ad_id}/delete</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-ads--ad_id--delete"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-ads--ad_id--delete"
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
                              name="Accept"                data-endpoint="POSTapi-ads--ad_id--delete"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ad_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="ad_id"                data-endpoint="POSTapi-ads--ad_id--delete"
               value="16"
               data-component="url">
    <br>
<p>The ID of the ad. Example: <code>16</code></p>
            </div>
                    </form>

                <h1 id="auth">Auth</h1>

    

                                <h2 id="auth-POSTapi-auth-otp-send">POST api/auth/otp/send</h2>

<p>
</p>



<span id="example-requests-POSTapi-auth-otp-send">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/auth/otp/send" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"mobile\": \"\\\"989123456789\\\"\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/auth/otp/send"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "mobile": "\"989123456789\""
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-auth-otp-send">
            <blockquote>
            <p>Example response (201, OTP created):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;OTP has been sent successfully.&quot;,
    &quot;data&quot;: {
        &quot;expires_in&quot;: 120
    },
    &quot;meta&quot;: {}
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Too many attempts):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Please wait before requesting another OTP.&quot;,
    &quot;errors&quot;: {
        &quot;retry_after&quot;: 75
    },
    &quot;data&quot;: null
}</code>
 </pre>
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
                              name="Content-Type"                data-endpoint="POSTapi-auth-otp-send"
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
                              name="Accept"                data-endpoint="POSTapi-auth-otp-send"
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
                              name="mobile"                data-endpoint="POSTapi-auth-otp-send"
               value=""989123456789""
               data-component="body">
    <br>
<p>The mobile number to send the OTP to. Must contain 10 to 15 digits. Example: <code>"989123456789"</code></p>
        </div>
        </form>

                    <h2 id="auth-POSTapi-auth-otp-verify">POST api/auth/otp/verify</h2>

<p>
</p>



<span id="example-requests-POSTapi-auth-otp-verify">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/auth/otp/verify" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"mobile\": \"\\\"989123456789\\\"\",
    \"otp\": \"\\\"123456\\\"\",
    \"username\": \"\\\"\\\"\",
    \"email\": \"\\\"\\\"\",
    \"first_name\": \"\\\"\\\"\",
    \"last_name\": \"\\\"\\\"\",
    \"birth_date\": \"\\\"\\\"\",
    \"national_id\": \"\\\"\\\"\",
    \"residence_city_id\": 10,
    \"residence_province_id\": 2
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/auth/otp/verify"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "mobile": "\"989123456789\"",
    "otp": "\"123456\"",
    "username": "\"\"",
    "email": "\"\"",
    "first_name": "\"\"",
    "last_name": "\"\"",
    "birth_date": "\"\"",
    "national_id": "\"\"",
    "residence_city_id": 10,
    "residence_province_id": 2
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-auth-otp-verify">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Authenticated successfully.&quot;,
    &quot;data&quot;: {
        &quot;access_token&quot;: &quot;eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...&quot;,
        &quot;refresh_token&quot;: &quot;f4b5c29f92f24f3b9e0a2d874e6c8f74b1e9f9e2a6f84715b22d8fca8f4b90de&quot;,
        &quot;token_type&quot;: &quot;Bearer&quot;,
        &quot;expires_in&quot;: 31536000,
        &quot;expires_at&quot;: &quot;2025-09-25 07:10:00&quot;,
        &quot;profile&quot;: {
            &quot;id&quot;: 12,
            &quot;first_name&quot;: &quot;Sara&quot;,
            &quot;last_name&quot;: &quot;Rahimi&quot;,
            &quot;full_name&quot;: &quot;Sara Rahimi&quot;,
            &quot;birth_date&quot;: &quot;1994-03-18&quot;,
            &quot;national_id&quot;: &quot;1234567890&quot;,
            &quot;residence_city_id&quot;: 10,
            &quot;residence_province_id&quot;: 2,
            &quot;user&quot;: {
                &quot;id&quot;: 45,
                &quot;mobile&quot;: &quot;989123456789&quot;,
                &quot;username&quot;: &quot;sara94&quot;,
                &quot;email&quot;: &quot;sara@example.com&quot;,
                &quot;roles&quot;: [
                    &quot;customer&quot;
                ],
                &quot;permissions&quot;: []
            },
            &quot;media&quot;: {
                &quot;national_id_document&quot;: &quot;https://cdn.example.com/media/national-id.pdf&quot;,
                &quot;profile_images&quot;: [
                    {
                        &quot;id&quot;: &quot;f17c6ae4-5c1a-4c44-a058-9324c4b6f8b9&quot;,
                        &quot;name&quot;: &quot;avatar&quot;,
                        &quot;url&quot;: &quot;https://cdn.example.com/media/avatar.jpg&quot;
                    }
                ]
            },
            &quot;created_at&quot;: &quot;2025-09-24T12:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-09-25T07:00:00.000000Z&quot;
        }
    },
    &quot;meta&quot;: {}
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Invalid OTP):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Invalid or expired OTP.&quot;,
    &quot;errors&quot;: {},
    &quot;data&quot;: null
}</code>
 </pre>
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
                              name="Content-Type"                data-endpoint="POSTapi-auth-otp-verify"
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
                              name="Accept"                data-endpoint="POSTapi-auth-otp-verify"
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
                              name="mobile"                data-endpoint="POSTapi-auth-otp-verify"
               value=""989123456789""
               data-component="body">
    <br>
<p>The mobile number the OTP was sent to. Must contain 10 to 15 digits. Example: <code>"989123456789"</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>otp</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="otp"                data-endpoint="POSTapi-auth-otp-verify"
               value=""123456""
               data-component="body">
    <br>
<p>The six-digit one-time password received by the user. Example: <code>"123456"</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>username</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="username"                data-endpoint="POSTapi-auth-otp-verify"
               value=""""
               data-component="body">
    <br>
<p>optional A unique username to assign to the user. Example: <code>""</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-auth-otp-verify"
               value=""""
               data-component="body">
    <br>
<p>optional An email address to associate with the user. Example: <code>""</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>first_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="first_name"                data-endpoint="POSTapi-auth-otp-verify"
               value=""""
               data-component="body">
    <br>
<p>optional User's given name. Example: <code>""</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>last_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="last_name"                data-endpoint="POSTapi-auth-otp-verify"
               value=""""
               data-component="body">
    <br>
<p>optional User's family name. Example: <code>""</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>birth_date</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="birth_date"                data-endpoint="POSTapi-auth-otp-verify"
               value=""""
               data-component="body">
    <br>
<p>optional Date of birth in Y-m-d format. Example: <code>""</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>national_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="national_id"                data-endpoint="POSTapi-auth-otp-verify"
               value=""""
               data-component="body">
    <br>
<p>optional National identification number. Example: <code>""</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>residence_city_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="residence_city_id"                data-endpoint="POSTapi-auth-otp-verify"
               value="10"
               data-component="body">
    <br>
<p>optional Identifier of the city where the user resides. Example: <code>10</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>residence_province_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="residence_province_id"                data-endpoint="POSTapi-auth-otp-verify"
               value="2"
               data-component="body">
    <br>
<p>optional Identifier of the province where the user resides. Example: <code>2</code></p>
        </div>
        </form>

                    <h2 id="auth-GETapi-auth-profile">GET api/auth/profile</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-auth-profile">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/auth/profile" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/auth/profile"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
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
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Profile retrieved successfully.&quot;,
    &quot;data&quot;: {
        &quot;profile&quot;: {
            &quot;id&quot;: 12,
            &quot;first_name&quot;: &quot;Sara&quot;,
            &quot;last_name&quot;: &quot;Rahimi&quot;,
            &quot;full_name&quot;: &quot;Sara Rahimi&quot;,
            &quot;birth_date&quot;: &quot;1994-03-18&quot;,
            &quot;national_id&quot;: &quot;1234567890&quot;,
            &quot;residence_city_id&quot;: 10,
            &quot;residence_province_id&quot;: 2,
            &quot;user&quot;: {
                &quot;id&quot;: 45,
                &quot;mobile&quot;: &quot;989123456789&quot;,
                &quot;username&quot;: &quot;sara94&quot;,
                &quot;email&quot;: &quot;sara@example.com&quot;,
                &quot;roles&quot;: [
                    &quot;customer&quot;
                ],
                &quot;permissions&quot;: []
            },
            &quot;media&quot;: {
                &quot;national_id_document&quot;: &quot;https://cdn.example.com/media/national-id.pdf&quot;,
                &quot;profile_images&quot;: [
                    {
                        &quot;id&quot;: &quot;f17c6ae4-5c1a-4c44-a058-9324c4b6f8b9&quot;,
                        &quot;name&quot;: &quot;avatar&quot;,
                        &quot;url&quot;: &quot;https://cdn.example.com/media/avatar.jpg&quot;
                    }
                ]
            },
            &quot;created_at&quot;: &quot;2025-09-24T12:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-09-25T07:00:00.000000Z&quot;
        }
    },
    &quot;meta&quot;: {}
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
      data-authed="1"
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
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-auth-profile"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-auth-profile"
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
                              name="Accept"                data-endpoint="GETapi-auth-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="auth-POSTapi-auth-profile">POST api/auth/profile</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-auth-profile">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/auth/profile" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "first_name="""\
    --form "last_name="""\
    --form "birth_date="""\
    --form "national_id="""\
    --form "residence_city_id=10"\
    --form "residence_province_id=2"\
    --form "profile_image=@C:\Users\Mohsen\AppData\Local\Temp\phpCC0.tmp" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/auth/profile"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('first_name', '""');
body.append('last_name', '""');
body.append('birth_date', '""');
body.append('national_id', '""');
body.append('residence_city_id', '10');
body.append('residence_province_id', '2');
body.append('profile_image', document.querySelector('input[name="profile_image"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-auth-profile">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Profile updated successfully.&quot;,
    &quot;data&quot;: {
        &quot;profile&quot;: {
            &quot;id&quot;: 12,
            &quot;first_name&quot;: &quot;Sara&quot;,
            &quot;last_name&quot;: &quot;Rahimi&quot;,
            &quot;full_name&quot;: &quot;Sara Rahimi&quot;,
            &quot;birth_date&quot;: &quot;1994-03-18&quot;,
            &quot;national_id&quot;: &quot;1234567890&quot;,
            &quot;residence_city_id&quot;: 10,
            &quot;residence_province_id&quot;: 2,
            &quot;user&quot;: {
                &quot;id&quot;: 45,
                &quot;mobile&quot;: &quot;989123456789&quot;,
                &quot;username&quot;: &quot;sara94&quot;,
                &quot;email&quot;: &quot;sara@example.com&quot;,
                &quot;roles&quot;: [
                    &quot;customer&quot;
                ],
                &quot;permissions&quot;: []
            },
            &quot;media&quot;: {
                &quot;national_id_document&quot;: &quot;https://cdn.example.com/media/national-id.pdf&quot;,
                &quot;profile_images&quot;: [
                    {
                        &quot;id&quot;: &quot;f17c6ae4-5c1a-4c44-a058-9324c4b6f8b9&quot;,
                        &quot;name&quot;: &quot;avatar&quot;,
                        &quot;url&quot;: &quot;https://cdn.example.com/media/avatar.jpg&quot;
                    }
                ]
            },
            &quot;created_at&quot;: &quot;2025-09-24T12:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-09-25T07:05:00.000000Z&quot;
        }
    },
    &quot;meta&quot;: {}
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;birth_date&quot;: [
            &quot;The birth date is not a valid date.&quot;
        ]
    },
    &quot;data&quot;: null
}</code>
 </pre>
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
      data-authed="1"
      data-hasfiles="1"
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
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-auth-profile"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-auth-profile"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-auth-profile"
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
                              name="first_name"                data-endpoint="POSTapi-auth-profile"
               value=""""
               data-component="body">
    <br>
<p>optional User's given name. Example: <code>""</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>last_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="last_name"                data-endpoint="POSTapi-auth-profile"
               value=""""
               data-component="body">
    <br>
<p>optional User's family name. Example: <code>""</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>birth_date</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="birth_date"                data-endpoint="POSTapi-auth-profile"
               value=""""
               data-component="body">
    <br>
<p>optional Date of birth in Y-m-d format. Example: <code>""</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>national_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="national_id"                data-endpoint="POSTapi-auth-profile"
               value=""""
               data-component="body">
    <br>
<p>optional National identification number. Example: <code>""</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>residence_city_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="residence_city_id"                data-endpoint="POSTapi-auth-profile"
               value="10"
               data-component="body">
    <br>
<p>optional Identifier of the city where the user resides. Example: <code>10</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>residence_province_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="residence_province_id"                data-endpoint="POSTapi-auth-profile"
               value="2"
               data-component="body">
    <br>
<p>optional Identifier of the province where the user resides. Example: <code>2</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>profile_image</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="file" style="display: none"
                              name="profile_image"                data-endpoint="POSTapi-auth-profile"
               value=""
               data-component="body">
    <br>
<p>optional Profile image file (JPEG, PNG, BMP, GIF, SVG, or WebP). Example: <code>C:\Users\Mohsen\AppData\Local\Temp\phpCC0.tmp</code></p>
        </div>
        </form>

                    <h2 id="auth-GETapi-auth-user">GET api/auth/user</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-auth-user">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/auth/user" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/auth/user"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
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
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;User retrieved successfully.&quot;,
    &quot;data&quot;: {
        &quot;user&quot;: {
            &quot;id&quot;: 45,
            &quot;mobile&quot;: &quot;989123456789&quot;,
            &quot;username&quot;: &quot;sara94&quot;,
            &quot;email&quot;: &quot;sara@example.com&quot;,
            &quot;roles&quot;: [
                &quot;customer&quot;
            ],
            &quot;permissions&quot;: [],
            &quot;created_at&quot;: &quot;2025-09-24T12:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-09-25T07:00:00.000000Z&quot;
        }
    },
    &quot;meta&quot;: {}
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
      data-authed="1"
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
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-auth-user"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-auth-user"
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
                              name="Accept"                data-endpoint="GETapi-auth-user"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="auth-POSTapi-auth-user">POST api/auth/user</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-auth-user">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/auth/user" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"username\": \"\\\"\\\"\",
    \"email\": \"\\\"\\\"\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/auth/user"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "username": "\"\"",
    "email": "\"\""
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-auth-user">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;User updated successfully.&quot;,
    &quot;data&quot;: {
        &quot;user&quot;: {
            &quot;id&quot;: 45,
            &quot;mobile&quot;: &quot;989123456789&quot;,
            &quot;username&quot;: &quot;sara94&quot;,
            &quot;email&quot;: &quot;sara@example.com&quot;,
            &quot;roles&quot;: [
                &quot;customer&quot;
            ],
            &quot;permissions&quot;: [],
            &quot;created_at&quot;: &quot;2025-09-24T12:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-09-25T07:10:00.000000Z&quot;
        }
    },
    &quot;meta&quot;: {}
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;username&quot;: [
            &quot;The username has already been taken.&quot;
        ]
    },
    &quot;data&quot;: null
}</code>
 </pre>
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
      data-authed="1"
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
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-auth-user"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-auth-user"
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
                              name="Accept"                data-endpoint="POSTapi-auth-user"
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
                              name="username"                data-endpoint="POSTapi-auth-user"
               value=""""
               data-component="body">
    <br>
<p>optional A unique username between 1 and 191 characters. Example: <code>""</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-auth-user"
               value=""""
               data-component="body">
    <br>
<p>optional A unique, valid email address. Example: <code>""</code></p>
        </div>
        </form>

                <h1 id="geography">Geography</h1>

    

                                <h2 id="geography-GETapi-geography-countries">List countries</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns a paginated list of countries.</p>

<span id="example-requests-GETapi-geography-countries">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/geography/countries?id=1&amp;name=%22%22&amp;name_en=%22%22&amp;capital_city=10&amp;per_page=25" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/geography/countries"
);

const params = {
    "id": "1",
    "name": """",
    "name_en": """",
    "capital_city": "10",
    "per_page": "25",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-geography-countries">
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
<span id="execution-results-GETapi-geography-countries" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-geography-countries"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-geography-countries"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-geography-countries" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-geography-countries">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-geography-countries" data-method="GET"
      data-path="api/geography/countries"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-geography-countries', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-geography-countries"
                    onclick="tryItOut('GETapi-geography-countries');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-geography-countries"
                    onclick="cancelTryOut('GETapi-geography-countries');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-geography-countries"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/geography/countries</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-geography-countries"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-geography-countries"
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
                              name="Accept"                data-endpoint="GETapi-geography-countries"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-geography-countries"
               value="1"
               data-component="query">
    <br>
<p>optional Filter by country ID. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="GETapi-geography-countries"
               value=""""
               data-component="query">
    <br>
<p>optional Filter by country name (fa/en, partial match). Example: <code>""</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>name_en</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="name_en"                data-endpoint="GETapi-geography-countries"
               value=""""
               data-component="query">
    <br>
<p>optional Filter by English name (partial match). Example: <code>""</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>capital_city</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="capital_city"                data-endpoint="GETapi-geography-countries"
               value="10"
               data-component="query">
    <br>
<p>optional Filter by capital city ID. Example: <code>10</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-geography-countries"
               value="25"
               data-component="query">
    <br>
<p>optional Results per page (1-100). Defaults to 50. Example: <code>25</code></p>
            </div>
                </form>

                    <h2 id="geography-GETapi-geography-countries--country_id-">Get a country</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Return details for a single country, including its capital and provinces.</p>

<span id="example-requests-GETapi-geography-countries--country_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/geography/countries/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/geography/countries/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-geography-countries--country_id-">
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
<span id="execution-results-GETapi-geography-countries--country_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-geography-countries--country_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-geography-countries--country_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-geography-countries--country_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-geography-countries--country_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-geography-countries--country_id-" data-method="GET"
      data-path="api/geography/countries/{country_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-geography-countries--country_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-geography-countries--country_id-"
                    onclick="tryItOut('GETapi-geography-countries--country_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-geography-countries--country_id-"
                    onclick="cancelTryOut('GETapi-geography-countries--country_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-geography-countries--country_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/geography/countries/{country_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-geography-countries--country_id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-geography-countries--country_id-"
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
                              name="Accept"                data-endpoint="GETapi-geography-countries--country_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>country_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="country_id"                data-endpoint="GETapi-geography-countries--country_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the country. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="country"                data-endpoint="GETapi-geography-countries--country_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the country. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="geography-GETapi-geography-provinces">List provinces</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns a paginated list of provinces.</p>

<span id="example-requests-GETapi-geography-provinces">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/geography/provinces?id=2&amp;name=%22%22&amp;name_en=%22%22&amp;country_id=1&amp;per_page=25" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/geography/provinces"
);

const params = {
    "id": "2",
    "name": """",
    "name_en": """",
    "country_id": "1",
    "per_page": "25",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-geography-provinces">
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
<span id="execution-results-GETapi-geography-provinces" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-geography-provinces"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-geography-provinces"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-geography-provinces" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-geography-provinces">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-geography-provinces" data-method="GET"
      data-path="api/geography/provinces"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-geography-provinces', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-geography-provinces"
                    onclick="tryItOut('GETapi-geography-provinces');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-geography-provinces"
                    onclick="cancelTryOut('GETapi-geography-provinces');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-geography-provinces"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/geography/provinces</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-geography-provinces"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-geography-provinces"
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
                              name="Accept"                data-endpoint="GETapi-geography-provinces"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-geography-provinces"
               value="2"
               data-component="query">
    <br>
<p>optional Filter by province ID. Example: <code>2</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="GETapi-geography-provinces"
               value=""""
               data-component="query">
    <br>
<p>optional Filter by province name (fa/en, partial match). Example: <code>""</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>name_en</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="name_en"                data-endpoint="GETapi-geography-provinces"
               value=""""
               data-component="query">
    <br>
<p>optional Filter by English name (partial match). Example: <code>""</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>country_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="country_id"                data-endpoint="GETapi-geography-provinces"
               value="1"
               data-component="query">
    <br>
<p>optional Filter by country ID. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-geography-provinces"
               value="25"
               data-component="query">
    <br>
<p>optional Results per page (1-100). Defaults to 50. Example: <code>25</code></p>
            </div>
                </form>

                    <h2 id="geography-GETapi-geography-provinces--province_id-">Get a province</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Return details for a single province, including its country and cities.</p>

<span id="example-requests-GETapi-geography-provinces--province_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/geography/provinces/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/geography/provinces/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-geography-provinces--province_id-">
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
<span id="execution-results-GETapi-geography-provinces--province_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-geography-provinces--province_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-geography-provinces--province_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-geography-provinces--province_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-geography-provinces--province_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-geography-provinces--province_id-" data-method="GET"
      data-path="api/geography/provinces/{province_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-geography-provinces--province_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-geography-provinces--province_id-"
                    onclick="tryItOut('GETapi-geography-provinces--province_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-geography-provinces--province_id-"
                    onclick="cancelTryOut('GETapi-geography-provinces--province_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-geography-provinces--province_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/geography/provinces/{province_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-geography-provinces--province_id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-geography-provinces--province_id-"
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
                              name="Accept"                data-endpoint="GETapi-geography-provinces--province_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>province_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="province_id"                data-endpoint="GETapi-geography-provinces--province_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the province. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>province</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="province"                data-endpoint="GETapi-geography-provinces--province_id-"
               value="2"
               data-component="url">
    <br>
<p>The ID of the province. Example: <code>2</code></p>
            </div>
                    </form>

                    <h2 id="geography-GETapi-geography-provinces--province_id--cities">List a province&#039;s cities</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns the cities that belong to the given province.</p>

<span id="example-requests-GETapi-geography-provinces--province_id--cities">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/geography/provinces/1/cities?per_page=25" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/geography/provinces/1/cities"
);

const params = {
    "per_page": "25",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-geography-provinces--province_id--cities">
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
<span id="execution-results-GETapi-geography-provinces--province_id--cities" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-geography-provinces--province_id--cities"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-geography-provinces--province_id--cities"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-geography-provinces--province_id--cities" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-geography-provinces--province_id--cities">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-geography-provinces--province_id--cities" data-method="GET"
      data-path="api/geography/provinces/{province_id}/cities"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-geography-provinces--province_id--cities', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-geography-provinces--province_id--cities"
                    onclick="tryItOut('GETapi-geography-provinces--province_id--cities');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-geography-provinces--province_id--cities"
                    onclick="cancelTryOut('GETapi-geography-provinces--province_id--cities');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-geography-provinces--province_id--cities"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/geography/provinces/{province_id}/cities</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-geography-provinces--province_id--cities"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-geography-provinces--province_id--cities"
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
                              name="Accept"                data-endpoint="GETapi-geography-provinces--province_id--cities"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>province_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="province_id"                data-endpoint="GETapi-geography-provinces--province_id--cities"
               value="1"
               data-component="url">
    <br>
<p>The ID of the province. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>province</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="province"                data-endpoint="GETapi-geography-provinces--province_id--cities"
               value="2"
               data-component="url">
    <br>
<p>The ID of the province. Example: <code>2</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-geography-provinces--province_id--cities"
               value="25"
               data-component="query">
    <br>
<p>optional Results per page (1-100). Defaults to 50. Example: <code>25</code></p>
            </div>
                </form>

                    <h2 id="geography-GETapi-geography-cities">List cities</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns a paginated list of cities.</p>

<span id="example-requests-GETapi-geography-cities">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/geography/cities?id=10&amp;name=%22%22&amp;name_en=%22%22&amp;province_id=2&amp;country_id=1&amp;latitude=35.6892&amp;longitude=51.389&amp;per_page=25" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/geography/cities"
);

const params = {
    "id": "10",
    "name": """",
    "name_en": """",
    "province_id": "2",
    "country_id": "1",
    "latitude": "35.6892",
    "longitude": "51.389",
    "per_page": "25",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-geography-cities">
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
<span id="execution-results-GETapi-geography-cities" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-geography-cities"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-geography-cities"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-geography-cities" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-geography-cities">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-geography-cities" data-method="GET"
      data-path="api/geography/cities"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-geography-cities', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-geography-cities"
                    onclick="tryItOut('GETapi-geography-cities');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-geography-cities"
                    onclick="cancelTryOut('GETapi-geography-cities');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-geography-cities"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/geography/cities</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-geography-cities"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-geography-cities"
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
                              name="Accept"                data-endpoint="GETapi-geography-cities"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-geography-cities"
               value="10"
               data-component="query">
    <br>
<p>optional Filter by city ID. Example: <code>10</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="GETapi-geography-cities"
               value=""""
               data-component="query">
    <br>
<p>optional Filter by city name (fa/en, partial match). Example: <code>""</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>name_en</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="name_en"                data-endpoint="GETapi-geography-cities"
               value=""""
               data-component="query">
    <br>
<p>optional Filter by English name (partial match). Example: <code>""</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>province_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="province_id"                data-endpoint="GETapi-geography-cities"
               value="2"
               data-component="query">
    <br>
<p>optional Filter by province ID. Example: <code>2</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>country_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="country_id"                data-endpoint="GETapi-geography-cities"
               value="1"
               data-component="query">
    <br>
<p>optional Filter by country ID. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>latitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="latitude"                data-endpoint="GETapi-geography-cities"
               value="35.6892"
               data-component="query">
    <br>
<p>optional Filter by exact latitude. Example: <code>35.6892</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>longitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="longitude"                data-endpoint="GETapi-geography-cities"
               value="51.389"
               data-component="query">
    <br>
<p>optional Filter by exact longitude. Example: <code>51.389</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-geography-cities"
               value="25"
               data-component="query">
    <br>
<p>optional Results per page (1-100). Defaults to 50. Example: <code>25</code></p>
            </div>
                </form>

                    <h2 id="geography-GETapi-geography-cities--city_id-">Get a city</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Return details for a single city, including its province and country.</p>

<span id="example-requests-GETapi-geography-cities--city_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/geography/cities/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/geography/cities/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-geography-cities--city_id-">
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
<span id="execution-results-GETapi-geography-cities--city_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-geography-cities--city_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-geography-cities--city_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-geography-cities--city_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-geography-cities--city_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-geography-cities--city_id-" data-method="GET"
      data-path="api/geography/cities/{city_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-geography-cities--city_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-geography-cities--city_id-"
                    onclick="tryItOut('GETapi-geography-cities--city_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-geography-cities--city_id-"
                    onclick="cancelTryOut('GETapi-geography-cities--city_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-geography-cities--city_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/geography/cities/{city_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-geography-cities--city_id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-geography-cities--city_id-"
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
                              name="Accept"                data-endpoint="GETapi-geography-cities--city_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>city_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="city_id"                data-endpoint="GETapi-geography-cities--city_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the city. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="city"                data-endpoint="GETapi-geography-cities--city_id-"
               value="10"
               data-component="url">
    <br>
<p>The ID of the city. Example: <code>10</code></p>
            </div>
                    </form>

                    <h2 id="geography-GETapi-geography-locations-lookup">Lookup nearby locations</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Finds nearby cities and provinces around the given coordinates.</p>

<span id="example-requests-GETapi-geography-locations-lookup">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/geography/locations/lookup?latitude=35.6892&amp;longitude=51.389&amp;radius_km=75&amp;city_limit=5&amp;province_limit=5" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/geography/locations/lookup"
);

const params = {
    "latitude": "35.6892",
    "longitude": "51.389",
    "radius_km": "75",
    "city_limit": "5",
    "province_limit": "5",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-geography-locations-lookup">
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
<span id="execution-results-GETapi-geography-locations-lookup" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-geography-locations-lookup"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-geography-locations-lookup"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-geography-locations-lookup" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-geography-locations-lookup">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-geography-locations-lookup" data-method="GET"
      data-path="api/geography/locations/lookup"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-geography-locations-lookup', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-geography-locations-lookup"
                    onclick="tryItOut('GETapi-geography-locations-lookup');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-geography-locations-lookup"
                    onclick="cancelTryOut('GETapi-geography-locations-lookup');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-geography-locations-lookup"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/geography/locations/lookup</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-geography-locations-lookup"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-geography-locations-lookup"
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
                              name="Accept"                data-endpoint="GETapi-geography-locations-lookup"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>latitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="latitude"                data-endpoint="GETapi-geography-locations-lookup"
               value="35.6892"
               data-component="query">
    <br>
<p>Latitude in degrees (-90 to 90). Example: <code>35.6892</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>longitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="longitude"                data-endpoint="GETapi-geography-locations-lookup"
               value="51.389"
               data-component="query">
    <br>
<p>Longitude in degrees (-180 to 180). Example: <code>51.389</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>radius_km</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="radius_km"                data-endpoint="GETapi-geography-locations-lookup"
               value="75"
               data-component="query">
    <br>
<p>optional Search radius in kilometers (0-1000). Defaults to 50. Example: <code>75</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>city_limit</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="city_limit"                data-endpoint="GETapi-geography-locations-lookup"
               value="5"
               data-component="query">
    <br>
<p>optional Maximum number of cities to return (1-100). Defaults to 10. Example: <code>5</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>province_limit</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="province_limit"                data-endpoint="GETapi-geography-locations-lookup"
               value="5"
               data-component="query">
    <br>
<p>optional Maximum number of provinces to return (1-100). Defaults to 10. Example: <code>5</code></p>
            </div>
                </form>

                    <h2 id="geography-GETapi-geography-locations-user-city">Resolve user&#039;s city</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Finds the nearest city to the given coordinates, within the maximum distance.</p>

<span id="example-requests-GETapi-geography-locations-user-city">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/geography/locations/user-city?latitude=35.7&amp;longitude=51.4&amp;max_distance_km=30" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/geography/locations/user-city"
);

const params = {
    "latitude": "35.7",
    "longitude": "51.4",
    "max_distance_km": "30",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-geography-locations-user-city">
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
<span id="execution-results-GETapi-geography-locations-user-city" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-geography-locations-user-city"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-geography-locations-user-city"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-geography-locations-user-city" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-geography-locations-user-city">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-geography-locations-user-city" data-method="GET"
      data-path="api/geography/locations/user-city"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-geography-locations-user-city', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-geography-locations-user-city"
                    onclick="tryItOut('GETapi-geography-locations-user-city');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-geography-locations-user-city"
                    onclick="cancelTryOut('GETapi-geography-locations-user-city');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-geography-locations-user-city"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/geography/locations/user-city</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-geography-locations-user-city"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-geography-locations-user-city"
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
                              name="Accept"                data-endpoint="GETapi-geography-locations-user-city"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>latitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="latitude"                data-endpoint="GETapi-geography-locations-user-city"
               value="35.7"
               data-component="query">
    <br>
<p>Latitude in degrees (-90 to 90). Example: <code>35.7</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>longitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="longitude"                data-endpoint="GETapi-geography-locations-user-city"
               value="51.4"
               data-component="query">
    <br>
<p>Longitude in degrees (-180 to 180). Example: <code>51.4</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>max_distance_km</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="max_distance_km"                data-endpoint="GETapi-geography-locations-user-city"
               value="30"
               data-component="query">
    <br>
<p>optional Maximum distance in kilometers (0-1000). Defaults to 50. Example: <code>30</code></p>
            </div>
                </form>

                    <h2 id="geography-GETapi-geography-locations-nearby-cities">Nearby cities</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns cities near the given coordinates, ordered by distance.</p>

<span id="example-requests-GETapi-geography-locations-nearby-cities">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/geography/locations/nearby-cities?latitude=35.6892&amp;longitude=51.389&amp;radius_km=100&amp;limit=8" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/geography/locations/nearby-cities"
);

const params = {
    "latitude": "35.6892",
    "longitude": "51.389",
    "radius_km": "100",
    "limit": "8",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-geography-locations-nearby-cities">
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
<span id="execution-results-GETapi-geography-locations-nearby-cities" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-geography-locations-nearby-cities"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-geography-locations-nearby-cities"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-geography-locations-nearby-cities" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-geography-locations-nearby-cities">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-geography-locations-nearby-cities" data-method="GET"
      data-path="api/geography/locations/nearby-cities"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-geography-locations-nearby-cities', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-geography-locations-nearby-cities"
                    onclick="tryItOut('GETapi-geography-locations-nearby-cities');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-geography-locations-nearby-cities"
                    onclick="cancelTryOut('GETapi-geography-locations-nearby-cities');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-geography-locations-nearby-cities"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/geography/locations/nearby-cities</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-geography-locations-nearby-cities"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-geography-locations-nearby-cities"
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
                              name="Accept"                data-endpoint="GETapi-geography-locations-nearby-cities"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>latitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="latitude"                data-endpoint="GETapi-geography-locations-nearby-cities"
               value="35.6892"
               data-component="query">
    <br>
<p>Latitude in degrees (-90 to 90). Example: <code>35.6892</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>longitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="longitude"                data-endpoint="GETapi-geography-locations-nearby-cities"
               value="51.389"
               data-component="query">
    <br>
<p>Longitude in degrees (-180 to 180). Example: <code>51.389</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>radius_km</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="radius_km"                data-endpoint="GETapi-geography-locations-nearby-cities"
               value="100"
               data-component="query">
    <br>
<p>optional Search radius in kilometers (0-1000). Defaults to 50. Example: <code>100</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>limit</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="limit"                data-endpoint="GETapi-geography-locations-nearby-cities"
               value="8"
               data-component="query">
    <br>
<p>optional Maximum number of cities to return (1-100). Defaults to 10. Example: <code>8</code></p>
            </div>
                </form>

                <h1 id="ads-review">Ads Review</h1>

    

                                <h2 id="ads-review-POSTapi-kpi-devices-register">POST api/kpi/devices/register</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-kpi-devices-register">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/kpi/devices/register" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"device_uuid\": \"6ff8f7f6-1eb3-3525-be4a-3932c805afed\",
    \"platform\": \"g\",
    \"app_version\": \"z\",
    \"os_version\": \"m\",
    \"device_model\": \"i\",
    \"device_manufacturer\": \"y\",
    \"locale\": \"ar_LY\",
    \"timezone\": \"Asia\\/Aqtau\",
    \"push_token\": \"l\",
    \"first_seen_at\": \"2025-10-05T20:35:36\",
    \"last_seen_at\": \"2025-10-05T20:35:36\",
    \"last_heartbeat_at\": \"2025-10-05T20:35:36\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/kpi/devices/register"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "device_uuid": "6ff8f7f6-1eb3-3525-be4a-3932c805afed",
    "platform": "g",
    "app_version": "z",
    "os_version": "m",
    "device_model": "i",
    "device_manufacturer": "y",
    "locale": "ar_LY",
    "timezone": "Asia\/Aqtau",
    "push_token": "l",
    "first_seen_at": "2025-10-05T20:35:36",
    "last_seen_at": "2025-10-05T20:35:36",
    "last_heartbeat_at": "2025-10-05T20:35:36"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-kpi-devices-register">
</span>
<span id="execution-results-POSTapi-kpi-devices-register" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-kpi-devices-register"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-kpi-devices-register"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-kpi-devices-register" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-kpi-devices-register">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-kpi-devices-register" data-method="POST"
      data-path="api/kpi/devices/register"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-kpi-devices-register', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-kpi-devices-register"
                    onclick="tryItOut('POSTapi-kpi-devices-register');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-kpi-devices-register"
                    onclick="cancelTryOut('POSTapi-kpi-devices-register');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-kpi-devices-register"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/kpi/devices/register</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-kpi-devices-register"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-kpi-devices-register"
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
                              name="Accept"                data-endpoint="POSTapi-kpi-devices-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>device_uuid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="device_uuid"                data-endpoint="POSTapi-kpi-devices-register"
               value="6ff8f7f6-1eb3-3525-be4a-3932c805afed"
               data-component="body">
    <br>
<p>Must be a valid UUID. Example: <code>6ff8f7f6-1eb3-3525-be4a-3932c805afed</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>platform</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="platform"                data-endpoint="POSTapi-kpi-devices-register"
               value="g"
               data-component="body">
    <br>
<p>Must not be greater than 50 characters. Example: <code>g</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>app_version</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="app_version"                data-endpoint="POSTapi-kpi-devices-register"
               value="z"
               data-component="body">
    <br>
<p>Must not be greater than 50 characters. Example: <code>z</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>os_version</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="os_version"                data-endpoint="POSTapi-kpi-devices-register"
               value="m"
               data-component="body">
    <br>
<p>Must not be greater than 100 characters. Example: <code>m</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>device_model</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="device_model"                data-endpoint="POSTapi-kpi-devices-register"
               value="i"
               data-component="body">
    <br>
<p>Must not be greater than 150 characters. Example: <code>i</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>device_manufacturer</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="device_manufacturer"                data-endpoint="POSTapi-kpi-devices-register"
               value="y"
               data-component="body">
    <br>
<p>Must not be greater than 150 characters. Example: <code>y</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>locale</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="locale"                data-endpoint="POSTapi-kpi-devices-register"
               value="ar_LY"
               data-component="body">
    <br>
<p>Must not be greater than 10 characters. Example: <code>ar_LY</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>timezone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="timezone"                data-endpoint="POSTapi-kpi-devices-register"
               value="Asia/Aqtau"
               data-component="body">
    <br>
<p>Must not be greater than 60 characters. Example: <code>Asia/Aqtau</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>push_token</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="push_token"                data-endpoint="POSTapi-kpi-devices-register"
               value="l"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>l</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>first_seen_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="first_seen_at"                data-endpoint="POSTapi-kpi-devices-register"
               value="2025-10-05T20:35:36"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2025-10-05T20:35:36</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>last_seen_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="last_seen_at"                data-endpoint="POSTapi-kpi-devices-register"
               value="2025-10-05T20:35:36"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2025-10-05T20:35:36</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>last_heartbeat_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="last_heartbeat_at"                data-endpoint="POSTapi-kpi-devices-register"
               value="2025-10-05T20:35:36"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2025-10-05T20:35:36</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>extra</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="extra"                data-endpoint="POSTapi-kpi-devices-register"
               value=""
               data-component="body">
    <br>

        </div>
        </form>

                    <h2 id="ads-review-POSTapi-kpi-devices-heartbeat">POST api/kpi/devices/heartbeat</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-kpi-devices-heartbeat">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/kpi/devices/heartbeat" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"device_uuid\": \"6ff8f7f6-1eb3-3525-be4a-3932c805afed\",
    \"last_seen_at\": \"2025-10-05T20:35:36\",
    \"last_heartbeat_at\": \"2025-10-05T20:35:36\",
    \"app_version\": \"g\",
    \"platform\": \"z\",
    \"os_version\": \"m\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/kpi/devices/heartbeat"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "device_uuid": "6ff8f7f6-1eb3-3525-be4a-3932c805afed",
    "last_seen_at": "2025-10-05T20:35:36",
    "last_heartbeat_at": "2025-10-05T20:35:36",
    "app_version": "g",
    "platform": "z",
    "os_version": "m"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-kpi-devices-heartbeat">
</span>
<span id="execution-results-POSTapi-kpi-devices-heartbeat" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-kpi-devices-heartbeat"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-kpi-devices-heartbeat"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-kpi-devices-heartbeat" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-kpi-devices-heartbeat">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-kpi-devices-heartbeat" data-method="POST"
      data-path="api/kpi/devices/heartbeat"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-kpi-devices-heartbeat', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-kpi-devices-heartbeat"
                    onclick="tryItOut('POSTapi-kpi-devices-heartbeat');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-kpi-devices-heartbeat"
                    onclick="cancelTryOut('POSTapi-kpi-devices-heartbeat');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-kpi-devices-heartbeat"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/kpi/devices/heartbeat</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-kpi-devices-heartbeat"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-kpi-devices-heartbeat"
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
                              name="Accept"                data-endpoint="POSTapi-kpi-devices-heartbeat"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>device_uuid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="device_uuid"                data-endpoint="POSTapi-kpi-devices-heartbeat"
               value="6ff8f7f6-1eb3-3525-be4a-3932c805afed"
               data-component="body">
    <br>
<p>Must be a valid UUID. Example: <code>6ff8f7f6-1eb3-3525-be4a-3932c805afed</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>last_seen_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="last_seen_at"                data-endpoint="POSTapi-kpi-devices-heartbeat"
               value="2025-10-05T20:35:36"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2025-10-05T20:35:36</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>last_heartbeat_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="last_heartbeat_at"                data-endpoint="POSTapi-kpi-devices-heartbeat"
               value="2025-10-05T20:35:36"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2025-10-05T20:35:36</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>app_version</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="app_version"                data-endpoint="POSTapi-kpi-devices-heartbeat"
               value="g"
               data-component="body">
    <br>
<p>Must not be greater than 50 characters. Example: <code>g</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>platform</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="platform"                data-endpoint="POSTapi-kpi-devices-heartbeat"
               value="z"
               data-component="body">
    <br>
<p>Must not be greater than 50 characters. Example: <code>z</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>os_version</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="os_version"                data-endpoint="POSTapi-kpi-devices-heartbeat"
               value="m"
               data-component="body">
    <br>
<p>Must not be greater than 100 characters. Example: <code>m</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>extra</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="extra"                data-endpoint="POSTapi-kpi-devices-heartbeat"
               value=""
               data-component="body">
    <br>

        </div>
        </form>

                    <h2 id="ads-review-POSTapi-kpi-installations">POST api/kpi/installations</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-kpi-installations">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/kpi/installations" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"device_uuid\": \"6ff8f7f6-1eb3-3525-be4a-3932c805afed\",
    \"installed_at\": \"2025-10-05T20:35:36\",
    \"app_version\": \"g\",
    \"platform\": \"z\",
    \"os_version\": \"m\",
    \"device_model\": \"i\",
    \"device_manufacturer\": \"y\",
    \"locale\": \"ar_LY\",
    \"timezone\": \"Asia\\/Aqtau\",
    \"push_token\": \"l\",
    \"install_source\": \"j\",
    \"campaign\": \"n\",
    \"is_reinstall\": false,
    \"user_id\": 16
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/kpi/installations"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "device_uuid": "6ff8f7f6-1eb3-3525-be4a-3932c805afed",
    "installed_at": "2025-10-05T20:35:36",
    "app_version": "g",
    "platform": "z",
    "os_version": "m",
    "device_model": "i",
    "device_manufacturer": "y",
    "locale": "ar_LY",
    "timezone": "Asia\/Aqtau",
    "push_token": "l",
    "install_source": "j",
    "campaign": "n",
    "is_reinstall": false,
    "user_id": 16
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-kpi-installations">
</span>
<span id="execution-results-POSTapi-kpi-installations" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-kpi-installations"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-kpi-installations"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-kpi-installations" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-kpi-installations">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-kpi-installations" data-method="POST"
      data-path="api/kpi/installations"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-kpi-installations', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-kpi-installations"
                    onclick="tryItOut('POSTapi-kpi-installations');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-kpi-installations"
                    onclick="cancelTryOut('POSTapi-kpi-installations');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-kpi-installations"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/kpi/installations</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-kpi-installations"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-kpi-installations"
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
                              name="Accept"                data-endpoint="POSTapi-kpi-installations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>device_uuid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="device_uuid"                data-endpoint="POSTapi-kpi-installations"
               value="6ff8f7f6-1eb3-3525-be4a-3932c805afed"
               data-component="body">
    <br>
<p>Must be a valid UUID. Example: <code>6ff8f7f6-1eb3-3525-be4a-3932c805afed</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>installed_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="installed_at"                data-endpoint="POSTapi-kpi-installations"
               value="2025-10-05T20:35:36"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2025-10-05T20:35:36</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>app_version</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="app_version"                data-endpoint="POSTapi-kpi-installations"
               value="g"
               data-component="body">
    <br>
<p>Must not be greater than 50 characters. Example: <code>g</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>platform</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="platform"                data-endpoint="POSTapi-kpi-installations"
               value="z"
               data-component="body">
    <br>
<p>Must not be greater than 50 characters. Example: <code>z</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>os_version</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="os_version"                data-endpoint="POSTapi-kpi-installations"
               value="m"
               data-component="body">
    <br>
<p>Must not be greater than 100 characters. Example: <code>m</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>device_model</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="device_model"                data-endpoint="POSTapi-kpi-installations"
               value="i"
               data-component="body">
    <br>
<p>Must not be greater than 150 characters. Example: <code>i</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>device_manufacturer</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="device_manufacturer"                data-endpoint="POSTapi-kpi-installations"
               value="y"
               data-component="body">
    <br>
<p>Must not be greater than 150 characters. Example: <code>y</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>locale</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="locale"                data-endpoint="POSTapi-kpi-installations"
               value="ar_LY"
               data-component="body">
    <br>
<p>Must not be greater than 10 characters. Example: <code>ar_LY</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>timezone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="timezone"                data-endpoint="POSTapi-kpi-installations"
               value="Asia/Aqtau"
               data-component="body">
    <br>
<p>Must not be greater than 60 characters. Example: <code>Asia/Aqtau</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>push_token</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="push_token"                data-endpoint="POSTapi-kpi-installations"
               value="l"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>l</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>install_source</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="install_source"                data-endpoint="POSTapi-kpi-installations"
               value="j"
               data-component="body">
    <br>
<p>Must not be greater than 100 characters. Example: <code>j</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>campaign</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="campaign"                data-endpoint="POSTapi-kpi-installations"
               value="n"
               data-component="body">
    <br>
<p>Must not be greater than 100 characters. Example: <code>n</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_reinstall</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-kpi-installations" style="display: none">
            <input type="radio" name="is_reinstall"
                   value="true"
                   data-endpoint="POSTapi-kpi-installations"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-kpi-installations" style="display: none">
            <input type="radio" name="is_reinstall"
                   value="false"
                   data-endpoint="POSTapi-kpi-installations"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>metadata</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="metadata"                data-endpoint="POSTapi-kpi-installations"
               value=""
               data-component="body">
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>extra</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="extra"                data-endpoint="POSTapi-kpi-installations"
               value=""
               data-component="body">
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="POSTapi-kpi-installations"
               value="16"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the users table. Example: <code>16</code></p>
        </div>
        </form>

                    <h2 id="ads-review-POSTapi-kpi-uninstallations">POST api/kpi/uninstallations</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-kpi-uninstallations">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/kpi/uninstallations" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"device_uuid\": \"6ff8f7f6-1eb3-3525-be4a-3932c805afed\",
    \"uninstalled_at\": \"2025-10-05T20:35:36\",
    \"app_version\": \"g\",
    \"platform\": \"z\",
    \"reason\": \"m\",
    \"report_source\": \"i\",
    \"user_id\": 16
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/kpi/uninstallations"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "device_uuid": "6ff8f7f6-1eb3-3525-be4a-3932c805afed",
    "uninstalled_at": "2025-10-05T20:35:36",
    "app_version": "g",
    "platform": "z",
    "reason": "m",
    "report_source": "i",
    "user_id": 16
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-kpi-uninstallations">
</span>
<span id="execution-results-POSTapi-kpi-uninstallations" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-kpi-uninstallations"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-kpi-uninstallations"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-kpi-uninstallations" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-kpi-uninstallations">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-kpi-uninstallations" data-method="POST"
      data-path="api/kpi/uninstallations"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-kpi-uninstallations', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-kpi-uninstallations"
                    onclick="tryItOut('POSTapi-kpi-uninstallations');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-kpi-uninstallations"
                    onclick="cancelTryOut('POSTapi-kpi-uninstallations');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-kpi-uninstallations"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/kpi/uninstallations</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-kpi-uninstallations"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-kpi-uninstallations"
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
                              name="Accept"                data-endpoint="POSTapi-kpi-uninstallations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>device_uuid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="device_uuid"                data-endpoint="POSTapi-kpi-uninstallations"
               value="6ff8f7f6-1eb3-3525-be4a-3932c805afed"
               data-component="body">
    <br>
<p>Must be a valid UUID. Example: <code>6ff8f7f6-1eb3-3525-be4a-3932c805afed</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>uninstalled_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="uninstalled_at"                data-endpoint="POSTapi-kpi-uninstallations"
               value="2025-10-05T20:35:36"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2025-10-05T20:35:36</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>app_version</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="app_version"                data-endpoint="POSTapi-kpi-uninstallations"
               value="g"
               data-component="body">
    <br>
<p>Must not be greater than 50 characters. Example: <code>g</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>platform</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="platform"                data-endpoint="POSTapi-kpi-uninstallations"
               value="z"
               data-component="body">
    <br>
<p>Must not be greater than 50 characters. Example: <code>z</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>reason</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="reason"                data-endpoint="POSTapi-kpi-uninstallations"
               value="m"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>m</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>report_source</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="report_source"                data-endpoint="POSTapi-kpi-uninstallations"
               value="i"
               data-component="body">
    <br>
<p>Must not be greater than 100 characters. Example: <code>i</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>metadata</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="metadata"                data-endpoint="POSTapi-kpi-uninstallations"
               value=""
               data-component="body">
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="POSTapi-kpi-uninstallations"
               value="16"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the users table. Example: <code>16</code></p>
        </div>
        </form>

                    <h2 id="ads-review-POSTapi-kpi-sessions">POST api/kpi/sessions</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-kpi-sessions">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/kpi/sessions" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"device_uuid\": \"6ff8f7f6-1eb3-3525-be4a-3932c805afed\",
    \"session_uuid\": \"6b72fe4a-5b40-307c-bc24-f79acf9a1bb9\",
    \"started_at\": \"2025-10-05T20:35:36\",
    \"ended_at\": \"2051-10-29\",
    \"duration_seconds\": 39,
    \"app_version\": \"g\",
    \"platform\": \"z\",
    \"os_version\": \"m\",
    \"network_type\": \"i\",
    \"city\": \"y\",
    \"country\": \"v\",
    \"user_id\": 16
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/kpi/sessions"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "device_uuid": "6ff8f7f6-1eb3-3525-be4a-3932c805afed",
    "session_uuid": "6b72fe4a-5b40-307c-bc24-f79acf9a1bb9",
    "started_at": "2025-10-05T20:35:36",
    "ended_at": "2051-10-29",
    "duration_seconds": 39,
    "app_version": "g",
    "platform": "z",
    "os_version": "m",
    "network_type": "i",
    "city": "y",
    "country": "v",
    "user_id": 16
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-kpi-sessions">
</span>
<span id="execution-results-POSTapi-kpi-sessions" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-kpi-sessions"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-kpi-sessions"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-kpi-sessions" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-kpi-sessions">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-kpi-sessions" data-method="POST"
      data-path="api/kpi/sessions"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-kpi-sessions', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-kpi-sessions"
                    onclick="tryItOut('POSTapi-kpi-sessions');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-kpi-sessions"
                    onclick="cancelTryOut('POSTapi-kpi-sessions');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-kpi-sessions"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/kpi/sessions</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-kpi-sessions"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-kpi-sessions"
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
                              name="Accept"                data-endpoint="POSTapi-kpi-sessions"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>device_uuid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="device_uuid"                data-endpoint="POSTapi-kpi-sessions"
               value="6ff8f7f6-1eb3-3525-be4a-3932c805afed"
               data-component="body">
    <br>
<p>Must be a valid UUID. Example: <code>6ff8f7f6-1eb3-3525-be4a-3932c805afed</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>session_uuid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="session_uuid"                data-endpoint="POSTapi-kpi-sessions"
               value="6b72fe4a-5b40-307c-bc24-f79acf9a1bb9"
               data-component="body">
    <br>
<p>Must be a valid UUID. Example: <code>6b72fe4a-5b40-307c-bc24-f79acf9a1bb9</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>started_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="started_at"                data-endpoint="POSTapi-kpi-sessions"
               value="2025-10-05T20:35:36"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2025-10-05T20:35:36</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>ended_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="ended_at"                data-endpoint="POSTapi-kpi-sessions"
               value="2051-10-29"
               data-component="body">
    <br>
<p>Must be a valid date. Must be a date after or equal to <code>started_at</code>. Example: <code>2051-10-29</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>duration_seconds</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="duration_seconds"                data-endpoint="POSTapi-kpi-sessions"
               value="39"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>39</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>app_version</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="app_version"                data-endpoint="POSTapi-kpi-sessions"
               value="g"
               data-component="body">
    <br>
<p>Must not be greater than 50 characters. Example: <code>g</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>platform</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="platform"                data-endpoint="POSTapi-kpi-sessions"
               value="z"
               data-component="body">
    <br>
<p>Must not be greater than 50 characters. Example: <code>z</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>os_version</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="os_version"                data-endpoint="POSTapi-kpi-sessions"
               value="m"
               data-component="body">
    <br>
<p>Must not be greater than 100 characters. Example: <code>m</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>network_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="network_type"                data-endpoint="POSTapi-kpi-sessions"
               value="i"
               data-component="body">
    <br>
<p>Must not be greater than 50 characters. Example: <code>i</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="city"                data-endpoint="POSTapi-kpi-sessions"
               value="y"
               data-component="body">
    <br>
<p>Must not be greater than 150 characters. Example: <code>y</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="country"                data-endpoint="POSTapi-kpi-sessions"
               value="v"
               data-component="body">
    <br>
<p>Must not be greater than 150 characters. Example: <code>v</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>metadata</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="metadata"                data-endpoint="POSTapi-kpi-sessions"
               value=""
               data-component="body">
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="POSTapi-kpi-sessions"
               value="16"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the users table. Example: <code>16</code></p>
        </div>
        </form>

                    <h2 id="ads-review-POSTapi-kpi-sessions--session_session_uuid--update">POST api/kpi/sessions/{session_session_uuid}/update</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-kpi-sessions--session_session_uuid--update">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/kpi/sessions/6ff8f7f6-1eb3-3525-be4a-3932c805afed/update" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    "ended_at": "2025-10-05T20:35:36",
    "duration_seconds": 27,
    "app_version": "n",
    "platform": "g",
    "os_version": "z",
    "network_type": "m",
    "city": "i",
    "country": "y",
    "user_id": 16
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/kpi/sessions/6ff8f7f6-1eb3-3525-be4a-3932c805afed/update"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "ended_at": "2025-10-05T20:35:36",
    "duration_seconds": 27,
    "app_version": "n",
    "platform": "g",
    "os_version": "z",
    "network_type": "m",
    "city": "i",
    "country": "y",
    "user_id": 16
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-kpi-sessions--session_session_uuid--update">
</span>
<span id="execution-results-POSTapi-kpi-sessions--session_session_uuid--update" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-kpi-sessions--session_session_uuid--update"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-kpi-sessions--session_session_uuid--update"
      data-empty-response-text="&lt;Empty response&gt;" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-kpi-sessions--session_session_uuid--update" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-kpi-sessions--session_session_uuid--update">

Tip: Check that you&amp;#039;re properly connected to the network.
If you&amp;#039;re a maintainer of ths API, verify that your API is running and you&amp;#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-kpi-sessions--session_session_uuid--update" data-method="POST"
      data-path="api/kpi/sessions/{session_session_uuid}/update"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-kpi-sessions--session_session_uuid--update', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-kpi-sessions--session_session_uuid--update"
                    onclick="tryItOut('POSTapi-kpi-sessions--session_session_uuid--update');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-kpi-sessions--session_session_uuid--update"
                    onclick="cancelTryOut('POSTapi-kpi-sessions--session_session_uuid--update');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-kpi-sessions--session_session_uuid--update"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/kpi/sessions/{session_session_uuid}/update</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-kpi-sessions--session_session_uuid--update"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-kpi-sessions--session_session_uuid--update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-kpi-sessions--session_session_uuid--update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style=" padding-left: 28px;  clear: unset;">
                <b style="line-height: 2;"><code>session_session_uuid</code></b>&nbsp;&nbsp;<small>string</small>&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="session_session_uuid"                data-endpoint="POSTapi-kpi-sessions--session_session_uuid--update"
               value="6ff8f7f6-1eb3-3525-be4a-3932c805afed"
               data-component="url">
    <br>
<p>The session UUID to update. Example: <code>6ff8f7f6-1eb3-3525-be4a-3932c805afed</code></p>
            </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>ended_at</code></b>&nbsp;&nbsp;<small>string</small>&nbsp;<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="ended_at"                data-endpoint="POSTapi-kpi-sessions--session_session_uuid--update"
               value="2025-10-05T20:35:36"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2025-10-05T20:35:36</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>duration_seconds</code></b>&nbsp;&nbsp;<small>integer</small>&nbsp;<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="duration_seconds"                data-endpoint="POSTapi-kpi-sessions--session_session_uuid--update"
               value="27"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>27</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>app_version</code></b>&nbsp;&nbsp;<small>string</small>&nbsp;<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="app_version"                data-endpoint="POSTapi-kpi-sessions--session_session_uuid--update"
               value="n"
               data-component="body">
    <br>
<p>Must not be greater than 50 characters. Example: <code>n</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>platform</code></b>&nbsp;&nbsp;<small>string</small>&nbsp;<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="platform"                data-endpoint="POSTapi-kpi-sessions--session_session_uuid--update"
               value="g"
               data-component="body">
    <br>
<p>Must not be greater than 50 characters. Example: <code>g</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>os_version</code></b>&nbsp;&nbsp;<small>string</small>&nbsp;<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="os_version"                data-endpoint="POSTapi-kpi-sessions--session_session_uuid--update"
               value="z"
               data-component="body">
    <br>
<p>Must not be greater than 100 characters. Example: <code>z</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>network_type</code></b>&nbsp;&nbsp;<small>string</small>&nbsp;<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="network_type"                data-endpoint="POSTapi-kpi-sessions--session_session_uuid--update"
               value="m"
               data-component="body">
    <br>
<p>Must not be greater than 50 characters. Example: <code>m</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;<small>string</small>&nbsp;<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="city"                data-endpoint="POSTapi-kpi-sessions--session_session_uuid--update"
               value="i"
               data-component="body">
    <br>
<p>Must not be greater than 150 characters. Example: <code>i</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;<small>string</small>&nbsp;<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="country"                data-endpoint="POSTapi-kpi-sessions--session_session_uuid--update"
               value="y"
               data-component="body">
    <br>
<p>Must not be greater than 150 characters. Example: <code>y</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>metadata</code></b>&nbsp;&nbsp;<small>object</small>&nbsp;<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="metadata"                data-endpoint="POSTapi-kpi-sessions--session_session_uuid--update"
               value=""
               data-component="body">
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;<small>integer</small>&nbsp;<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="POSTapi-kpi-sessions--session_session_uuid--update"
               value="16"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the users table. Example: <code>16</code></p>
        </div>
        </form>

                    <h2 id="ads-review-POSTapi-kpi-events">POST api/kpi/events</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-kpi-events">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/kpi/events" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"device_uuid\": \"6ff8f7f6-1eb3-3525-be4a-3932c805afed\",
    \"session_uuid\": \"6b72fe4a-5b40-307c-bc24-f79acf9a1bb9\",
    \"platform\": \"m\",
    \"user_id\": 16,
    \"events\": [
        {
            \"event_uuid\": \"6ff8f7f6-1eb3-3525-be4a-3932c805afed\",
            \"event_key\": \"g\",
            \"event_name\": \"z\",
            \"event_category\": \"m\",
            \"event_value\": 4326.41688,
            \"occurred_at\": \"2025-10-05T20:35:36\"
        }
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/kpi/events"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "device_uuid": "6ff8f7f6-1eb3-3525-be4a-3932c805afed",
    "session_uuid": "6b72fe4a-5b40-307c-bc24-f79acf9a1bb9",
    "platform": "m",
    "user_id": 16,
    "events": [
        {
            "event_uuid": "6ff8f7f6-1eb3-3525-be4a-3932c805afed",
            "event_key": "g",
            "event_name": "z",
            "event_category": "m",
            "event_value": 4326.41688,
            "occurred_at": "2025-10-05T20:35:36"
        }
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-kpi-events">
</span>
<span id="execution-results-POSTapi-kpi-events" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-kpi-events"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-kpi-events"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-kpi-events" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-kpi-events">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-kpi-events" data-method="POST"
      data-path="api/kpi/events"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-kpi-events', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-kpi-events"
                    onclick="tryItOut('POSTapi-kpi-events');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-kpi-events"
                    onclick="cancelTryOut('POSTapi-kpi-events');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-kpi-events"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/kpi/events</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-kpi-events"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-kpi-events"
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
                              name="Accept"                data-endpoint="POSTapi-kpi-events"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>device_uuid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="device_uuid"                data-endpoint="POSTapi-kpi-events"
               value="6ff8f7f6-1eb3-3525-be4a-3932c805afed"
               data-component="body">
    <br>
<p>Must be a valid UUID. Example: <code>6ff8f7f6-1eb3-3525-be4a-3932c805afed</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>session_uuid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="session_uuid"                data-endpoint="POSTapi-kpi-events"
               value="6b72fe4a-5b40-307c-bc24-f79acf9a1bb9"
               data-component="body">
    <br>
<p>Must be a valid UUID. Example: <code>6b72fe4a-5b40-307c-bc24-f79acf9a1bb9</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>platform</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="platform"                data-endpoint="POSTapi-kpi-events"
               value="m"
               data-component="body">
    <br>
<p>Must not be greater than 50 characters. Example: <code>m</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="POSTapi-kpi-events"
               value="16"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the users table. Example: <code>16</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>events</code></b>&nbsp;&nbsp;
<small>object[]</small>&nbsp;
 &nbsp;
<br>
<p>Must have at least 1 items.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>event_uuid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="events.0.event_uuid"                data-endpoint="POSTapi-kpi-events"
               value="6ff8f7f6-1eb3-3525-be4a-3932c805afed"
               data-component="body">
    <br>
<p>Must be a valid UUID. Example: <code>6ff8f7f6-1eb3-3525-be4a-3932c805afed</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>event_key</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="events.0.event_key"                data-endpoint="POSTapi-kpi-events"
               value="g"
               data-component="body">
    <br>
<p>Must not be greater than 100 characters. Example: <code>g</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>event_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="events.0.event_name"                data-endpoint="POSTapi-kpi-events"
               value="z"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>z</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>event_category</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="events.0.event_category"                data-endpoint="POSTapi-kpi-events"
               value="m"
               data-component="body">
    <br>
<p>Must not be greater than 100 characters. Example: <code>m</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>event_value</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="events.0.event_value"                data-endpoint="POSTapi-kpi-events"
               value="4326.41688"
               data-component="body">
    <br>
<p>Example: <code>4326.41688</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>occurred_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="events.0.occurred_at"                data-endpoint="POSTapi-kpi-events"
               value="2025-10-05T20:35:36"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2025-10-05T20:35:36</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>metadata</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="events.0.metadata"                data-endpoint="POSTapi-kpi-events"
               value=""
               data-component="body">
    <br>

                    </div>
                                    </details>
        </div>
        </form>

                <h1 id="settings">Settings</h1>

                    <h2 id="settings-GETapi-v1-settings">Display a listing of the resource.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-settings">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/v1/settings" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/v1/settings"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-settings">
            <blockquote>
            <p>Example response (500):</p>
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
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-settings" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-settings"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-settings"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-settings" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-settings">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-settings" data-method="GET"
      data-path="api/v1/settings"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-settings', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-settings"
                    onclick="tryItOut('GETapi-v1-settings');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-settings"
                    onclick="cancelTryOut('GETapi-v1-settings');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-settings"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/settings</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-settings"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-settings"
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
                              name="Accept"                data-endpoint="GETapi-v1-settings"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="settings-POSTapi-v1-settings">Store a newly created resource in storage.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-settings">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/v1/settings" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/v1/settings"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-settings">
</span>
<span id="execution-results-POSTapi-v1-settings" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-settings"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-settings"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-settings" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-settings">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-settings" data-method="POST"
      data-path="api/v1/settings"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-settings', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-settings"
                    onclick="tryItOut('POSTapi-v1-settings');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-settings"
                    onclick="cancelTryOut('POSTapi-v1-settings');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-settings"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/settings</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-settings"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-settings"
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
                              name="Accept"                data-endpoint="POSTapi-v1-settings"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="settings-GETapi-v1-settings--id-">Show the specified resource.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-settings--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/v1/settings/architecto" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/v1/settings/architecto"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-settings--id-">
            <blockquote>
            <p>Example response (500):</p>
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
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-settings--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-settings--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-settings--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-settings--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-settings--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-settings--id-" data-method="GET"
      data-path="api/v1/settings/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-settings--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-settings--id-"
                    onclick="tryItOut('GETapi-v1-settings--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-settings--id-"
                    onclick="cancelTryOut('GETapi-v1-settings--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-settings--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/settings/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-settings--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-settings--id-"
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
                              name="Accept"                data-endpoint="GETapi-v1-settings--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="GETapi-v1-settings--id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the setting. Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="settings-PUTapi-v1-settings--id-">Update the specified resource in storage.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-settings--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "https://api.wezone.app/api/v1/settings/architecto" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/v1/settings/architecto"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-settings--id-">
</span>
<span id="execution-results-PUTapi-v1-settings--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-settings--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-settings--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-settings--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-settings--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-settings--id-" data-method="PUT"
      data-path="api/v1/settings/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-settings--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-settings--id-"
                    onclick="tryItOut('PUTapi-v1-settings--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-settings--id-"
                    onclick="cancelTryOut('PUTapi-v1-settings--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-settings--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/settings/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/settings/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-settings--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-settings--id-"
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
                              name="Accept"                data-endpoint="PUTapi-v1-settings--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="PUTapi-v1-settings--id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the setting. Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="settings-DELETEapi-v1-settings--id-">Remove the specified resource from storage.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-DELETEapi-v1-settings--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "https://api.wezone.app/api/v1/settings/architecto" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/v1/settings/architecto"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-settings--id-">
</span>
<span id="execution-results-DELETEapi-v1-settings--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-settings--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-settings--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-settings--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-settings--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-settings--id-" data-method="DELETE"
      data-path="api/v1/settings/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-settings--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-settings--id-"
                    onclick="tryItOut('DELETEapi-v1-settings--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-settings--id-"
                    onclick="cancelTryOut('DELETEapi-v1-settings--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-settings--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/settings/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-v1-settings--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-settings--id-"
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
                              name="Accept"                data-endpoint="DELETEapi-v1-settings--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="DELETEapi-v1-settings--id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the setting. Example: <code>architecto</code></p>
            </div>
                    </form>

                <h1 id="users">Users</h1>

                    <h2 id="users-GETapi-users--user_id--followers">GET api/users/{user_id}/followers</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-users--user_id--followers">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/users/1/followers?followed_from=2025-01-01&amp;followed_to=2025-12-31&amp;per_page=25" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/users/1/followers"
);

const params = {
    "followed_from": "2025-01-01",
    "followed_to": "2025-12-31",
    "per_page": "25",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-users--user_id--followers">
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
<span id="execution-results-GETapi-users--user_id--followers" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-users--user_id--followers"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-users--user_id--followers"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-users--user_id--followers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-users--user_id--followers">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-users--user_id--followers" data-method="GET"
      data-path="api/users/{user_id}/followers"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-users--user_id--followers', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-users--user_id--followers"
                    onclick="tryItOut('GETapi-users--user_id--followers');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-users--user_id--followers"
                    onclick="cancelTryOut('GETapi-users--user_id--followers');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-users--user_id--followers"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/users/{user_id}/followers</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-users--user_id--followers"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-users--user_id--followers"
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
                              name="Accept"                data-endpoint="GETapi-users--user_id--followers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="GETapi-users--user_id--followers"
               value="1"
               data-component="url">
    <br>
<p>The ID of the user. Example: <code>1</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>followed_from</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="followed_from"                data-endpoint="GETapi-users--user_id--followers"
               value="2025-01-01"
               data-component="query">
    <br>
<p>Start date (inclusive) for follow records (YYYY-MM-DD). Must be a valid date. Example: <code>2025-01-01</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>followed_to</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="followed_to"                data-endpoint="GETapi-users--user_id--followers"
               value="2025-12-31"
               data-component="query">
    <br>
<p>End date (inclusive). Must be &gt;= followed_from. Must be a valid date. Must be a date after or equal to <code>followed_from</code>. Example: <code>2025-12-31</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-users--user_id--followers"
               value="25"
               data-component="query">
    <br>
<p>Items per page (1–100). Must be at least 1. Must not be greater than 100. Example: <code>25</code></p>
            </div>
                </form>

                    <h2 id="users-GETapi-users">GET api/users</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-users">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.wezone.app/api/users?follower_id=42&amp;email=jane%40example.com&amp;mobile=09123456789&amp;username=jane_doe&amp;per_page=20" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/users"
);

const params = {
    "follower_id": "42",
    "email": "jane@example.com",
    "mobile": "09123456789",
    "username": "jane_doe",
    "per_page": "20",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-users">
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
<span id="execution-results-GETapi-users" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-users"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-users"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-users" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-users">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-users" data-method="GET"
      data-path="api/users"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-users', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-users"
                    onclick="tryItOut('GETapi-users');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-users"
                    onclick="cancelTryOut('GETapi-users');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-users"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/users</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-users"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-users"
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
                              name="Accept"                data-endpoint="GETapi-users"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>follower_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="follower_id"                data-endpoint="GETapi-users"
               value="42"
               data-component="query">
    <br>
<p>Filter users followed by this user ID. The <code>id</code> of an existing record in the users table. Example: <code>42</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="GETapi-users"
               value="jane@example.com"
               data-component="query">
    <br>
<p>Filter by email (partial match allowed in your controller). Example: <code>jane@example.com</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>mobile</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="mobile"                data-endpoint="GETapi-users"
               value="09123456789"
               data-component="query">
    <br>
<p>Filter by mobile number. Example: <code>09123456789</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>username</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="username"                data-endpoint="GETapi-users"
               value="jane_doe"
               data-component="query">
    <br>
<p>Filter by username. Example: <code>jane_doe</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-users"
               value="20"
               data-component="query">
    <br>
<p>Items per page (1–100). Must be at least 1. Must not be greater than 100. Example: <code>20</code></p>
            </div>
                </form>

                <h1 id="users">Users</h1>

    

                                <h2 id="users-POSTapi-users--user_id--follow">Follow a user.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-users--user_id--follow">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/users/1/follow" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/users/1/follow"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-users--user_id--follow">
</span>
<span id="execution-results-POSTapi-users--user_id--follow" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-users--user_id--follow"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-users--user_id--follow"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-users--user_id--follow" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-users--user_id--follow">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-users--user_id--follow" data-method="POST"
      data-path="api/users/{user_id}/follow"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-users--user_id--follow', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-users--user_id--follow"
                    onclick="tryItOut('POSTapi-users--user_id--follow');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-users--user_id--follow"
                    onclick="cancelTryOut('POSTapi-users--user_id--follow');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-users--user_id--follow"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/users/{user_id}/follow</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-users--user_id--follow"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-users--user_id--follow"
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
                              name="Accept"                data-endpoint="POSTapi-users--user_id--follow"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="POSTapi-users--user_id--follow"
               value="1"
               data-component="url">
    <br>
<p>The ID of the user. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user"                data-endpoint="POSTapi-users--user_id--follow"
               value="123"
               data-component="url">
    <br>
<p>The ID of the user to follow. Example: <code>123</code></p>
            </div>
                    </form>

                    <h2 id="users-POSTapi-users--user_id--unfollow">Unfollow a user.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-users--user_id--unfollow">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.wezone.app/api/users/1/unfollow" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.wezone.app/api/users/1/unfollow"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-users--user_id--unfollow">
</span>
<span id="execution-results-POSTapi-users--user_id--unfollow" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-users--user_id--unfollow"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-users--user_id--unfollow"
      data-empty-response-text="&lt;Empty response&gt;" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-users--user_id--unfollow" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-users--user_id--unfollow">

Tip: Check that you&amp;#039;re properly connected to the network.
If you&amp;#039;re a maintainer of ths API, verify that your API is running and you&amp;#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-users--user_id--unfollow" data-method="POST"
      data-path="api/users/{user_id}/unfollow"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-users--user_id--unfollow', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-users--user_id--unfollow"
                    onclick="tryItOut('POSTapi-users--user_id--unfollow');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-users--user_id--unfollow"
                    onclick="cancelTryOut('POSTapi-users--user_id--unfollow');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-users--user_id--unfollow"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/users/{user_id}/unfollow</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-users--user_id--unfollow"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-users--user_id--unfollow"
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
                              name="Accept"                data-endpoint="POSTapi-users--user_id--unfollow"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;<small>integer</small>&nbsp;
&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="POSTapi-users--user_id--unfollow"
               value="1"
               data-component="url">
    <br>
<p>The ID of the user. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user</code></b>&nbsp;&nbsp;<small>integer</small>&nbsp;
&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user"                data-endpoint="POSTapi-users--user_id--unfollow"
               value="123"
               data-component="url">
    <br>
<p>The ID of the user to unfollow. Example: <code>123</code></p>
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
