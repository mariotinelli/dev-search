<?php

function generateCpf(): string
{
    $cpf = [];

    for ($i = 0; $i < 9; $i++) {
        $cpf[] = rand(0, 9);
    }

    $cpf[] = calculateDigit($cpf, 10);

    $cpf[] = calculateDigit($cpf, 11);

    return implode('', $cpf);
}

function calculateDigit(array $cpf, int $weight): int
{
    $sum = 0;

    for ($i = 0; $i < count($cpf); $i++) {
        $sum += $cpf[$i] * $weight--;
    }

    $remainder = $sum % 11;

    return $remainder < 2 ? 0 : 11 - $remainder;
}
