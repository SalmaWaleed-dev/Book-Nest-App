<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        $ids = collect($cart)->pluck('book_id')->unique();
        $books = Book::with('category')->whereIn('id', $ids)->get()->keyBy('id');

        $items = collect($cart)->map(function (array $item) use ($books) {
            if ($item['type'] === 'bundle') {
                $bundleBooks = Book::with('category')->whereIn('id', $item['book_ids'] ?? [])->get();
                if ($bundleBooks->count() < 3) return null;
                $item['books'] = $bundleBooks;
                $item['subtotal'] = (float) ($item['bundle_price'] ?? ($bundleBooks->sum(fn ($b) => (float)$b->price) * .85));
                return $item;
            }
            $book = $books->get($item['book_id']);
            if (!$book) return null;
            $item['book'] = $book;
            $item['subtotal'] = $item['type'] === 'buy' ? (float) $book->price * $item['quantity'] : 0;
            return $item;
        })->filter()->values();

        $total = $items->sum('subtotal');
        return view('cart.index', compact('items', 'total'));
    }

    public function add(Request $request, Book $book)
    {
        $data = $request->validate(['type' => ['required', 'in:buy,borrow']]);
        if ($data['type'] === 'borrow' && $book->available_copies < 1) {
            return back()->with('status', 'This book is currently unavailable for borrowing.');
        }

        $cart = $request->session()->get('cart', []);
        $key = collect($cart)->search(fn ($item) => $item['book_id'] === $book->id && $item['type'] === $data['type']);

        if ($key !== false) {
            $cart[$key]['quantity']++;
        } else {
            $cart[] = ['book_id' => $book->id, 'type' => $data['type'], 'quantity' => 1];
        }

        $request->session()->put('cart', array_values($cart));
        return back()->with('status', ucfirst($data['type']) . ' added to your cart.');
    }


    public function addBundle(Request $request)
    {
        $data = $request->validate(['book_ids' => ['required', 'array', 'size:3'], 'book_ids.*' => ['integer', 'distinct', 'exists:books,id']]);
        $books = Book::whereIn('id', $data['book_ids'])->get();
        if ($books->count() !== 3) return back()->with('status', 'This bundle is no longer available.');
        $cart = $request->session()->get('cart', []);
        $cart[] = [
            'type' => 'bundle',
            'book_ids' => array_values($books->pluck('id')->all()),
            'bundle_price' => round($books->sum(fn ($book) => (float) $book->price) * .85, 2),
            'quantity' => 1,
        ];
        $request->session()->put('cart', array_values($cart));
        return back()->with('status', 'Bundle added to your cart with 15% off.');
    }

    public function remove(Request $request, Book $book, string $type)
    {
        abort_unless(in_array($type, ['buy', 'borrow'], true), 404);
        $cart = collect($request->session()->get('cart', []))
            ->reject(fn ($item) => $item['book_id'] === $book->id && $item['type'] === $type)
            ->values()->all();
        $request->session()->put('cart', $cart);
        return back()->with('status', 'Item removed from cart.');
    }

    public function clear(Request $request)
    {
        $request->session()->forget('cart');
        return back()->with('status', 'Cart cleared.');
    }

    public function checkout(Request $request)
    {
        $items = collect($request->session()->get('cart', []));
        if ($items->isEmpty()) {
            return back()->with('status', 'Your cart is empty.');
        }

        foreach ($items->where('type', 'borrow') as $item) {
            $book = Book::find($item['book_id']);
            if (!$book || $book->available_copies < $item['quantity']) {
                return back()->with('status', 'One of the borrowed books no longer has enough available copies.');
            }
        }

        // The project has no payment/order backend, so checkout remains a safe
        // local cart action. Borrowed copies are reserved by decrementing stock;
        // bought items are only presented as a cart until a payment workflow exists.
        foreach ($items->where('type', 'borrow') as $item) {
            Book::whereKey($item['book_id'])->decrement('available_copies', $item['quantity']);
        }

        $hasBuy = $items->contains('type', 'buy');
        $hasBorrow = $items->contains('type', 'borrow');
        $request->session()->forget('cart');

        $message = $hasBuy && $hasBorrow
            ? 'Your borrow request was recorded and the cart was cleared. Buy items are ready for a future payment workflow.'
            : ($hasBorrow ? 'Your borrow request was recorded and the cart was cleared.' : 'Your cart was cleared. A payment gateway is not configured in this project yet.');

        return redirect()->route('cart.index')->with('status', $message);
    }
}
