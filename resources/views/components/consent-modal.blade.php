    
     <!-- CONSENT MODAL -->
    <div id="consentOverlay"
         style="position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.65);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;">

        <div style="width: 420px;
                    height: 420px;
                    background: #fff;
                    border-radius: 8px;
                    box-shadow: 0 15px 40px rgba(0,0,0,0.25);
                    display: flex;
                    flex-direction: column;">

            <div style="display:flex;
                        justify-content:space-between;
                        align-items:center;
                        padding:16px;
                        border-bottom:1px solid #e5e7eb;">

                <h2 style="font-weight:600; font-size:16px;">
                    User Consent on Personal Data Usage
                </h2>

                <button onclick="redirectHome()"
                        style="font-size:22px;
                               background:none;
                               border:none;
                               cursor:pointer;">
                    ×
                </button>

            </div>


            <div style="padding:16px;
                        font-size:14px;
                        line-height:1.6;
                        flex:1;
                        overflow:auto;">

                By signing up, you agree to the collection and use of your personal data
                solely for the purpose of providing and improving the services of this platform.
                Your data will not be shared, sold, or used for any other purposes without your
                explicit consent. You can withdraw your consent at any time by contacting us.
                Please review our
                <a href="https://ico.org.uk/for-organisations/uk-gdpr-guidance-and-resources/"
                   style="color:#2563eb; text-decoration:underline"
                   target="_blank">
                    Privacy Policy
                </a>
                for more details.

                <div style="margin-top:20px;">
                    <label>
                        <input type="checkbox"
                               id="consentCheckbox"
                               onchange="toggleProceed()" />

                        I agree to the use of my personal data as described above.
                    </label>
                </div>

            </div>


            <div style="padding:16px;
                        border-top:1px solid #e5e7eb;
                        text-align:right;">

                <button id="proceedBtn"
                        disabled
                        onclick="acceptConsent()"
                        style="padding:8px 18px;
                               border-radius:6px;
                               border:none;
                               background:#16a34a;
                               color:#fff;
                               opacity:0.5;
                               cursor:not-allowed;">
                    Proceed to Register
                </button>

            </div>

        </div>

    </div>