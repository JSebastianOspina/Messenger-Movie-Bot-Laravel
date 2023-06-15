<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <script>
                    function statusChangeCallback(response) {
                        // Called with the results from FB.getLoginStatus().
                        console.log("statusChangeCallback");
                        console.log(response); // The current login status of the person.
                        if (response.status === "connected") {
                            // Logged into your webpage and Facebook.
                            testAPI();
                        } else {
                            // Not logged into your webpage or we are unable to tell.
                            document.getElementById("status").innerHTML =
                                "Status: Not logged";
                        }
                    }

                    function checkLoginState() {
                        // Called when a person is finished with the Login Button.
                        FB.getLoginStatus(function (response) {
                            // See the onlogin handler
                            statusChangeCallback(response);
                        });
                    }

                    window.fbAsyncInit = function () {
                        FB.init({
                            appId: "284088670728253",
                            cookie: true,
                            xfbml: true,
                            version: "v16.0", // Use this Graph API version for this call.
                        });

                        FB.getLoginStatus(function (response) {
                            // Called after the JS SDK has been initialized.
                            statusChangeCallback(response); // Returns the login status.
                        });
                    };

                    function testAPI() {
                        // Testing Graph API after login.  See statusChangeCallback() for when this call is made.
                        console.log("Welcome!  Fetching your information.... ");
                        FB.api("/me", function (response) {
                            console.log("Successful login for: " + response.name);
                            document.getElementById("status").innerHTML =
                                "Thanks for logging in, " + response.name + "!";
                        });
                    }
                </script>
                <div class="flex flex-col justify-center items-center">
                    <p>Por favor, loggeate y selecciona la pagina donde quieras configurar el bot.</p><br>
                    <fb:login-button scope="public_profile,email,pages_messaging"
                                     onlogin="checkLoginState();" >
                    </fb:login-button>
                </div>


                <div id="status" class="text-center">
                </div>

                <!-- Load the JS SDK asynchronously -->
                <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>


            </div>
        </div>
    </div>
</x-app-layout>
