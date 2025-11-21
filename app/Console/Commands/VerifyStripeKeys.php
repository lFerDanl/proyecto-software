<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VerifyStripeKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica si las claves de Stripe están configuradas correctamente';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $stripeKey = env('STRIPE_KEY');
        $stripeSecret = env('STRIPE_SECRET');

        $this->info('Verificando configuración de Stripe...');
        
        if (empty($stripeKey)) {
            $this->error('La clave publicable de Stripe (STRIPE_KEY) no está configurada en el archivo .env');
            $this->info('Por favor, agrega la clave STRIPE_KEY a tu archivo .env');
        } else {
            $this->info('✓ La clave publicable de Stripe (STRIPE_KEY) está configurada: ' . substr($stripeKey, 0, 8) . '...');
            
            // Verificar formato de la clave publicable
            if (!preg_match('/^pk_test_/', $stripeKey) && !preg_match('/^pk_live_/', $stripeKey)) {
                $this->warn('Advertencia: La clave publicable no parece tener el formato correcto. Debe comenzar con "pk_test_" o "pk_live_"');
            }
        }
        
        if (empty($stripeSecret)) {
            $this->error('La clave secreta de Stripe (STRIPE_SECRET) no está configurada en el archivo .env');
            $this->info('Por favor, agrega la clave STRIPE_SECRET a tu archivo .env');
        } else {
            $this->info('✓ La clave secreta de Stripe (STRIPE_SECRET) está configurada: ' . substr($stripeSecret, 0, 8) . '...');
            
            // Verificar formato de la clave secreta
            if (!preg_match('/^sk_test_/', $stripeSecret) && !preg_match('/^sk_live_/', $stripeSecret)) {
                $this->warn('Advertencia: La clave secreta no parece tener el formato correcto. Debe comenzar con "sk_test_" o "sk_live_"');
            }
        }
        
        if (!empty($stripeKey) && !empty($stripeSecret)) {
            $this->info('✓ Todas las claves de Stripe están configuradas correctamente');
        }
        
        return 0;
    }
}
