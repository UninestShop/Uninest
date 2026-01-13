<!DOCTYPE html>
<html>
<head>
    <title>Your Product Has Been Rejected</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Your Product Has Been Rejected</h2>
        </div>
        
        <p>Hello {{ $name }},</p>
        
        <p>Your product "{{ $product->name }}" has been rejected.</p>
        
        <p>Please review your product details and make any necessary changes.</p>
        
        <!-- Commented out action button as per request
        <a href="{{ $url }}" class="button">Edit Your Product</a>
        -->
        
        <p>If you have any questions, please contact our support team.</p>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Student Products. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
