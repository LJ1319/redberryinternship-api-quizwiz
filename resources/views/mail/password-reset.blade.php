<x-layout>
    <x-slot:title>
        QuizWiz Password Reset
    </x-slot:title>

    <x-slot:subject>
        Reset your password
    </x-slot:subject>

    <x-slot:recipient>
        {{ $username }}
    </x-slot:recipient>

    <x-slot:expiration>
        {{ $expiration->addMinute()->diffForHumans() }}
    </x-slot:expiration>

    <x-slot:description>
        Follow the link to reset your password.
    </x-slot:description>

    <x-slot:url>
        {{ $away }}?resetUrl={{ $resetUrl }}&token={{ $token }}&email={{ $email }}
    </x-slot:url>

    <x-slot:action>
        Reset now
    </x-slot:action>
</x-layout>
