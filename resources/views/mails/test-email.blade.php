<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }`
        .max-w-2xl {
            max-width: 42rem;
            margin: auto;
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
        .border-b {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .flex {
            display: flex;
            align-items: center;
        }
        .w-64 {
            width: 16rem;
        }
        .h-28 {
            height: 7rem;
        }
        .bg-primary {
            background-color: #2563eb;
        }
        .h-10 {
            height: 2.5rem;
        }
        .w-10 {
            width: 2.5rem;
        }
        .rounded {
            border-radius: 0.375rem;
        }
        .justify-center {
            justify-content: center;
        }
        .text-white {
            color: #ffffff;
        }
        .font-bold {
            font-weight: bold;
        }
        .text-xl {
            font-size: 1.25rem;
        }
        .bg-green-100 {
            background-color: #d1fae5;
        }
        .p-3 {
            padding: 0.75rem;
        }
        .rounded-full {
            border-radius: 9999px;
        }
        .h-8 {
            height: 2rem;
        }
        .w-8 {
            width: 2rem;
        }
        .text-green-600 {
            color: #16a34a;
        }
        .text-center {
            text-align: center;
        }
        .text-2xl {
            font-size: 1.5rem;
        }
        .text-gray-900 {
            color: #111827;
        }
        .text-gray-600 {
            color: #4b5563;
        }
        .bg-gray-50 {
            background-color: #f9fafb;
        }
        .border-gray-100 {
            border: 1px solid #f3f4f6;
        }
        .p-4 {
            padding: 1rem;
        }
        .rounded-lg {
            border-radius: 0.5rem;
        }
        .font-medium {
            font-weight: 500;
        }
        .grid {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 0.5rem;
            font-size: 0.875rem;
        }
        .text-gray-500 {
            color: #6b7280;
        }
        .text-gray-800 {
            color: #1f2937;
        }
        .mt-8 {
            margin-top: 2rem;
        }
        .pt-6 {
            padding-top: 1.5rem;
        }
        .border-t {
            border-top: 1px solid #e5e7eb;
        }
        .text-xs {
            font-size: 0.75rem;
        }
        .text-gray-400 {
            color: #9ca3af;
        }
        .mt-1 {
            margin-top: 0.25rem;
        }
        .space-x-2 > * + * {
            margin-left: 0.5rem;
        }
        .hover\:text-primary:hover {
            color: #2563eb;
        }
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="max-w-2xl">
        <div class="flex border-b">
            <img src="{{ $message->embedData($imageData, 'images/spartav_logo.png') }}" alt="SPARTAV" class="w-64 h-28">
        </div>

        <div class="flex justify-center">
            <div class="bg-green-100 p-3 rounded-full">
                <img src="{{ $message->embedData($imageData2, 'images/check.png') }}" alt="Check" class="h-8 w-8 text-green-600">
            </div>
        </div>

        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-900">Account Successfully Activated</h1>
            <p class="text-gray-600">Thank you for confirming your email address. Your account has been activated and is now ready to use.</p>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
            <h2 class="font-medium text-gray-800">Account Information</h2>
            <div class="grid">
                <span class="text-gray-500">Email:</span>
                <span class="text-gray-800">{{ $user->name }}</span>
                <span class="text-gray-500">Username:</span>
                <span class="text-gray-800">{{ $user->email }}</span>
                <span class="text-gray-500">Activated on:</span>
                <span class="text-gray-800">{{ now()->format('F j, Y \a\t g:i A') }}</span>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t text-center text-xs text-gray-400">
            <p>&copy; 2025 Acme Inc. All rights reserved.</p>
            <p class="mt-1">123 Business Street, Suite 100, San Francisco, CA 94107</p>
            <div class="mt-2 space-x-2">
                <span class="hover:text-primary cursor-pointer">Privacy Policy</span>
                <span>•</span>
                <span class="hover:text-primary cursor-pointer">Terms of Service</span>
                <span>•</span>
                <span class="hover:text-primary cursor-pointer">Unsubscribe</span>
            </div>
        </div>
    </div>
</body>
</html>
