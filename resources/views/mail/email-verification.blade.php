<x-layout>
    <x-slot:title>
        QuizWiz Email Verification
    </x-slot:title>

    <x-slot:subject>
        Verify your email address to get started
    </x-slot:subject>

    <x-slot:recipient>
        {{ $username }}
    </x-slot:recipient>

    <x-slot:expiration>
        {{ $expiration->addMinute()->diffForHumans() }}
    </x-slot:expiration>

    <x-slot:description>
        To complete your signup, please verify your email address.
    </x-slot:description>

    <x-slot:url>
        {{ $away }}?verificationUrl={{ $verificationUrl }}
    </x-slot:url>

    <x-slot:action>
        Verify now
    </x-slot:action>
</x-layout>
