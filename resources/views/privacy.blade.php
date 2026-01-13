@extends('layouts.app')

@section('content')
<div class="container py-5">
  <div class="row">
    <div class="col-12">
      <h1 class="mb-4 cms-heading">Privacy Policy</h1>
      <div class="card cms-main">
        <div class="card-body">
          <h2>Your Privacy Matters</h2>
          <p>This Privacy Policy explains how we collect, use, and protect your personal information when you use our university marketplace platform.</p>
          
          <h3 class="mt-4">1. Information We Collect</h3>
          <p>We collect the following types of information:</p>
          <ul>
            <li><strong>Personal Information:</strong> Name, email address, phone number, university ID.</li>
            <li><strong>Account Information:</strong> Username, password (encrypted).</li>
            <li><strong>Transaction Information:</strong> Details of items you buy or sell, payment methods used.</li>
            <li><strong>Usage Information:</strong> How you interact with our platform, including browsing history and search queries.</li>
            <li><strong>Device Information:</strong> IP address, browser type, device type, and operating system.</li>
          </ul>
          
          <h3 class="mt-4">2. How We Use Your Information</h3>
          <p>We use your information to:</p>
          <ul>
            <li>Create and manage your account.</li>
            <li>Enable buying and selling of items.</li>
            <li>Improve our platform and services.</li>
            <li>Communicate with you about your account or transactions.</li>
            <li>Send updates about platform changes or new features.</li>
            <li>Prevent, detect, and investigate fraud or abuse.</li>
          </ul>
          
          <h3 class="mt-4">3. Information Sharing</h3>
          <p>We may share your information with:</p>
          <ul>
            <li><strong>Other Users:</strong> When you engage in transactions, certain information will be shared with the other party.</li>
            <li><strong>Service Providers:</strong> Companies that help us operate our platform.</li>
            <li><strong>University Administration:</strong> If required for security purposes or policy enforcement.</li>
            <li><strong>Legal Authorities:</strong> If required by law or to protect our rights.</li>
          </ul>
          
          <h3 class="mt-4">4. Data Security</h3>
          <p>We implement appropriate security measures to protect your information from unauthorized access, alteration, or disclosure. However, no internet transmission is completely secure, so we cannot guarantee absolute security.</p>
          
          <h3 class="mt-4">5. Your Rights</h3>
          <p>You have the right to:</p>
          <ul>
            <li>Access your personal information.</li>
            <li>Correct inaccurate information.</li>
            <li>Delete your account and associated data.</li>
            <li>Object to how we use your information.</li>
            <li>Export your data in a portable format.</li>
          </ul>
          
          <h3 class="mt-4">6. Cookies and Tracking</h3>
          <p>We use cookies and similar technologies to enhance your experience, remember your preferences, and analyze platform usage. You can manage your cookie preferences through your browser settings.</p>
          
          <h3 class="mt-4">7. Changes to This Policy</h3>
          <p>We may update this Privacy Policy periodically. We will notify you of significant changes through the platform or your registered email address.</p>
          
          <h3 class="mt-4">8. Contact Us</h3>
          <p>If you have any questions or concerns about this Privacy Policy, please contact us at privacy@universitymarketplace.com or through our <a href="{{ url('/contact') }}">Contact page</a>.</p>
          
          <p class="mt-5">Last updated: {{ date('F j, Y') }}</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection