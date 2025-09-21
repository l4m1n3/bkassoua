<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validation = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone_number' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
           

        ]); 

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->string('password')),
          
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home'));
    }
    public function profile()
    {
        $user = Auth::user();
        $orders = $user->orders; // Historique des commandes
        return view('user.profile', compact('user', 'orders'));
    }

    public function addToWishlist($productId)
    {
        $user = Auth::user();
        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $productId,
        ]);
        return redirect()->back()->with('success', 'Produit ajouté à votre liste de souhaits.');
    }

    public function addToCart($productId)
    {
        $user = Auth::user();
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id, 'product_id' => $productId],
            ['quantity' => 1]
        );
        return redirect()->back()->with('success', 'Produit ajouté au panier.');
    }

    public function viewCart()
    {
        $cart = Auth::user()->carts;
        return view('user.cart', compact('cart'));
    }

    public function placeOrder()
    {
        // Calcul du prix total et création de la commande
        $user = Auth::user();
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => 100, // Calcul du prix total
            'status' => 'pending',
        ]);
        return redirect()->route('user.orders');
    }
   
}
