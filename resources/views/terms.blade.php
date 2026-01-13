@extends('layouts.app')

@section('content')
<div class="container py-5">
  <div class="row">
    <div class="col-12">
      <h1 class="mb-4 cms-heading">Terms and Conditions</h1>
      <div class="card cms-main">
        <div class="card-body">
          <h2>Welcome to the University Marketplace</h2>
          <p>These terms and conditions outline the rules and regulations for the use of our university marketplace platform.</p>
          
          <h3 class="mt-4">1. Account Creation and Usage</h3>
          <p>By registering on our platform, you confirm that:</p>
          <ul>
            <li>You are a current student, faculty member, or staff of the university.</li>
            <li>The information you provide is accurate and complete.</li>
            <li>You will maintain the security of your account credentials.</li>
            <li>You will not share your account with others.</li>
          </ul>
          
          <h3 class="mt-4">2. Listing Items</h3>
          <p>When listing items for sale, you agree that:</p>
          <ul>
            <li>You are the rightful owner of the item or authorized to sell it.</li>
            <li>The item description is accurate and complete.</li>
            <li>The price is clearly stated.</li>
            <li>You will not list prohibited items (illegal goods, services, etc.).</li>
          </ul>
          
          <h3 class="mt-4">3. Buying Items</h3>
          <p>As a buyer on our platform, you agree to:</p>
          <ul>
            <li>Pay the agreed price for items you purchase.</li>
            <li>Arrange pickup or delivery in a timely manner.</li>
            <li>Inspect items before completing the transaction.</li>
          </ul>
          
          <h3 class="mt-4">4. Communication</h3>
          <p>Users agree to maintain respectful communication and not to:</p>
          <ul>
            <li>Harass or intimidate other users.</li>
            <li>Use offensive or inappropriate language.</li>
            <li>Share personal information of others.</li>
          </ul>
          
          <h3 class="mt-4">5. Liability</h3>
          <p>Our platform serves as a connection point between buyers and sellers. We are not responsible for:</p>
          <ul>
            <li>The quality, safety, or legality of listed items.</li>
            <li>The accuracy of listing descriptions.</li>
            <li>The behavior of users.</li>
            <li>Any loss or damage resulting from transactions.</li>
          </ul>
          
          <h3 class="mt-4">6. Termination</h3>
          <p>We reserve the right to suspend or terminate accounts that violate these terms or engage in inappropriate behavior on our platform.</p>
          
          <h3 class="mt-4">7. Changes to Terms</h3>
          <p>We may modify these terms at any time. Continued use of the platform after changes constitutes acceptance of the revised terms.</p>
          
          <p class="mt-5">Last updated: {{ date('F j, Y') }}</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
