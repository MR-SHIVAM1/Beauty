<?php
/**
 * First-boot seed data. Runs once, only when app.sqlite is created fresh.
 */

function seed_database(PDO $pdo): void
{
    seed_users($pdo);
    seed_promo_codes($pdo);
    seed_products($pdo);
}

function seed_users(PDO $pdo): void
{
    $stmt = $pdo->prepare(
        'INSERT INTO users (name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->execute(['Store Admin', 'admin@honeybeauty.local', '9999999999', password_hash('admin', PASSWORD_BCRYPT), 'admin']);
}

function seed_promo_codes(PDO $pdo): void
{
    $stmt = $pdo->prepare(
        'INSERT INTO promo_codes (code, discount_percent, active) VALUES (?, ?, 1)'
    );
    $stmt->execute(['WELCOME10', 10]);
    $stmt->execute(['HONEY20', 20]);
    $stmt->execute(['GLOW15', 15]);
}

function seed_products(PDO $pdo): void
{
    $productStmt = $pdo->prepare(
        'INSERT INTO products (name, slug, category, price, mrp, rating, image, short_desc, description, ingredients, stock)
         VALUES (:name, :slug, :category, :price, :mrp, :rating, :image, :short_desc, :description, :ingredients, :stock)'
    );
    $reviewStmt = $pdo->prepare(
        'INSERT INTO product_reviews (product_id, reviewer_name, rating, review_text) VALUES (?, ?, ?, ?)'
    );

    foreach (product_catalog() as $p) {
        $productStmt->execute([
            ':name' => $p['name'],
            ':slug' => $p['slug'],
            ':category' => $p['category'],
            ':price' => $p['price'],
            ':mrp' => $p['mrp'],
            ':rating' => $p['rating'],
            ':image' => $p['image'],
            ':short_desc' => $p['short_desc'],
            ':description' => $p['description'],
            ':ingredients' => json_encode($p['ingredients']),
            ':stock' => $p['stock'] ?? rand(20, 120),
        ]);
        $productId = (int) $pdo->lastInsertId();
        foreach ($p['reviews'] as $r) {
            $reviewStmt->execute([$productId, $r[0], $r[1], $r[2]]);
        }
    }
}

function product_catalog(): array
{
    return [
        // ---------------- MAKEUP ----------------
        ['name' => 'Honey Beauty Velvet Matte Lipstick', 'slug' => 'velvet-matte-lipstick', 'category' => 'makeup', 'price' => 799, 'mrp' => 999, 'rating' => 4.8, 'image' => 'lipstick.jpg',
            'short_desc' => 'A 12-hour velvety matte finish that never feels dry.',
            'description' => 'Crafted with natural waxes and richly pigmented in a single swipe, this lipstick delivers a soft-focus matte finish that lasts through meals and meetings alike. Lightweight, buildable, and comfortable on the lips.',
            'ingredients' => ['Vitamin E', 'Jojoba Oil', 'Mineral Pigments', 'Shea Butter'],
            'reviews' => [['Pooja T.', 5, 'Best quality ever, stays on all day!'], ['Ritika S.', 5, 'Color payoff is incredible.'], ['Anushka V.', 4, 'Slightly drying but worth it for the pigment.']]],
        ['name' => 'Honey Beauty Glass Liquid Lipstick', 'slug' => 'glass-liquid-lipstick', 'category' => 'makeup', 'price' => 650, 'mrp' => 799, 'rating' => 4.7, 'image' => 'liquid-lipstick.jpg',
            'short_desc' => 'Smudge-proof liquid color with a comfortable matte-satin finish.',
            'description' => 'A weightless liquid-to-matte lipstick that sets in seconds and resists transfer, smudging, and fading. The fine-tip applicator makes precision lining effortless.',
            'ingredients' => ['Argan Oil', 'Coconut Oil', 'Vitamin E'],
            'reviews' => [['Nisha M.', 5, 'Perfect for long work days.'], ['Farah K.', 4, 'Love the finish, wish there were more shades.']]],
        ['name' => 'Honey Beauty Plumping Lip Gloss', 'slug' => 'plumping-lip-gloss', 'category' => 'makeup', 'price' => 550, 'mrp' => 650, 'rating' => 4.7, 'image' => 'lip-gloss.jpg',
            'short_desc' => 'Non-sticky, glass-like shine with a subtle plumping tingle.',
            'description' => 'This high-shine gloss glides on smooth and reflects light like glass, while a gentle plumping complex adds natural-looking fullness without stickiness.',
            'ingredients' => ['Coconut Oil', 'Shimmer Pearls', 'Peppermint Extract'],
            'reviews' => [['Aanya V.', 5, 'Very juicy finish, smells amazing.'], ['Ira D.', 5, 'My go-to for everyday wear.']]],
        ['name' => 'Honey Beauty Precision Lip Liner', 'slug' => 'precision-lip-liner', 'category' => 'makeup', 'price' => 350, 'mrp' => 450, 'rating' => 4.4, 'image' => 'lip-liner.jpg',
            'short_desc' => 'Ultra-creamy, long-wear definition that never bleeds.',
            'description' => 'Glides on effortlessly to define and fill lips with a soft, blendable texture that keeps lipstick from feathering, all day long.',
            'ingredients' => ['Jojoba Oil', 'Synthetic Wax', 'Vitamin E'],
            'reviews' => [['Meera P.', 4, 'Does not bleed, exactly as promised.'], ['Simone R.', 5, 'Creamy and precise.']]],
        ['name' => 'Honey Beauty HD Liquid Foundation', 'slug' => 'hd-liquid-foundation', 'category' => 'makeup', 'price' => 1299, 'mrp' => 1599, 'rating' => 4.7, 'image' => 'foundation.jpg',
            'short_desc' => 'Professional, buildable coverage that blurs pores instantly.',
            'description' => 'A weightless, high-definition foundation that blurs the look of pores and fine lines while letting your natural skin texture breathe. Buildable from sheer to full coverage.',
            'ingredients' => ['Hyaluronic Acid', 'Aloe Extracts', 'SPF 15'],
            'reviews' => [['Riya S.', 5, 'Flawless finish, my skin looks airbrushed.'], ['Divya N.', 4, 'Great match to my skin tone.']]],
        ['name' => 'Honey Beauty Vivid Eyeshadow Palette', 'slug' => 'vivid-eyeshadow-palette', 'category' => 'makeup', 'price' => 1599, 'mrp' => 1999, 'rating' => 4.9, 'image' => 'eyeshadow.jpg',
            'short_desc' => '12 richly pigmented shades for day-to-night looks.',
            'description' => 'A versatile edit of mattes and shimmers, blendable and buildable, designed to take any look from a soft daytime wash of color to a smoky night-out finish.',
            'ingredients' => ['Mica', 'Mineral Oil', 'Vitamin E'],
            'reviews' => [['Kajal P.', 5, 'Amazing colors, blends like a dream.'], ['Tanya B.', 5, 'Zero fallout, very pigmented.']]],
        ['name' => 'Honey Beauty Organic Rose Blush', 'slug' => 'organic-rose-blush', 'category' => 'makeup', 'price' => 650, 'mrp' => 799, 'rating' => 4.6, 'image' => 'blush.jpg',
            'short_desc' => 'Talc-free powder blush for a natural, healthy flush.',
            'description' => 'A silky-soft powder blush infused with rose pigment that melts into skin for a natural, healthy-looking flush that lasts all day.',
            'ingredients' => ['Rose Petal Powder', 'Vitamin C', 'Talc-Free Base'],
            'reviews' => [['Anjali D.', 4, 'Very natural finish.'], ['Payal M.', 5, 'Buildable and pretty.']]],
        ['name' => 'Honey Beauty Volume Mascara', 'slug' => 'volume-mascara', 'category' => 'makeup', 'price' => 750, 'mrp' => 899, 'rating' => 4.5, 'image' => 'mascara.jpg',
            'short_desc' => 'Waterproof formula for dramatic length and thickness.',
            'description' => 'A clump-free, waterproof mascara that builds volume and length lash by lash, holding curl through humidity, tears, and long days.',
            'ingredients' => ['Charcoal Pigments', 'Castor Oil', 'Beeswax'],
            'reviews' => [['Sneha K.', 4, 'Long lasting, no smudging.'], ['Bhavna J.', 5, 'Great volume without clumps.']]],
        ['name' => 'Honey Beauty Silk-Touch Matte Primer', 'slug' => 'silk-touch-matte-primer', 'category' => 'makeup', 'price' => 899, 'mrp' => 1099, 'rating' => 4.5, 'image' => 'primer.jpg',
            'short_desc' => 'Pore-filling, oil-controlling velvet canvas for makeup.',
            'description' => 'A silicone-based gel primer that fills in pores and fine lines, controls shine, and helps makeup glide on and stay put for hours.',
            'ingredients' => ['Zinc Oxide', 'Glycerin', 'Silicone Blend'],
            'reviews' => [['Mital P.', 4, 'Smooth base, makeup lasts longer.'], ['Ruchi A.', 5, 'Skin looks so smooth after this.']]],
        ['name' => 'Honey Beauty Diamond Glow Highlighter', 'slug' => 'diamond-glow-highlighter', 'category' => 'makeup', 'price' => 699, 'mrp' => 849, 'rating' => 4.8, 'image' => 'highlighter.jpg',
            'short_desc' => 'Liquid highlighter with micro-shimmer for lit-from-within glow.',
            'description' => 'A featherlight liquid highlighter that melts into skin and catches light beautifully for a lit-from-within glow that photographs like a dream.',
            'ingredients' => ['Micro-Shimmer Dust', 'Shea Butter', 'Vitamin E'],
            'reviews' => [['Sandhya R.', 5, 'Stunning glow, so buildable.'], ['Zoya F.', 5, 'Best highlighter I have used.']]],
        ['name' => 'Honey Beauty Sculpt Contour Stick', 'slug' => 'sculpt-contour-stick', 'category' => 'makeup', 'price' => 750, 'mrp' => 899, 'rating' => 4.3, 'image' => 'contour.jpg',
            'short_desc' => 'Creamy, blendable contour for effortless sculpting.',
            'description' => 'A cream contour stick that blends seamlessly with fingers or a brush for natural, sculpted definition without harsh lines.',
            'ingredients' => ['Ozokerite Wax', 'Shea Butter', 'Vitamin E'],
            'reviews' => [['Tanvi S.', 4, 'Easy to blend, natural shadow.'], ['Kritika N.', 4, 'Great for beginners.']]],
        ['name' => 'Honey Beauty Waterproof Eyeliner', 'slug' => 'waterproof-eyeliner', 'category' => 'makeup', 'price' => 550, 'mrp' => 650, 'rating' => 4.6, 'image' => 'eyeliner.jpg',
            'short_desc' => 'Deep matte black, precise felt-tip applicator.',
            'description' => 'An intensely pigmented, smudge-resistant eyeliner with a fine felt tip for crisp lines and dramatic wings in one confident stroke.',
            'ingredients' => ['Carbon Black', 'Film-Forming Polymers'],
            'reviews' => [['Riddhi J.', 4, 'Dries fast, no smudging.'], ['Aisha M.', 5, 'Sharpest wings ever.']]],
        ['name' => 'Honey Beauty Defining Eyebrow Pencil', 'slug' => 'defining-eyebrow-pencil', 'category' => 'makeup', 'price' => 450, 'mrp' => 549, 'rating' => 4.5, 'image' => 'eyebrow-pencil.jpg',
            'short_desc' => 'Fine-tip pencil for natural, hair-like brow strokes.',
            'description' => 'A retractable, fine-tip pencil that fills sparse brows with natural-looking hair strokes and a spoolie end for effortless blending.',
            'ingredients' => ['Plant Wax', 'Vitamin E'],
            'reviews' => [['Khushi M.', 5, 'So natural looking.'], ['Devika R.', 4, 'Great precision tip.']]],
        ['name' => 'Honey Beauty 16-Hour Setting Spray', 'slug' => '16-hour-setting-spray', 'category' => 'makeup', 'price' => 650, 'mrp' => 799, 'rating' => 4.7, 'image' => 'setting-spray.jpg',
            'short_desc' => 'Weightless mist that locks makeup in place all day.',
            'description' => 'A fine, weightless mist infused with skin-soothing botanicals that locks makeup in place for up to 16 hours while keeping skin fresh and hydrated.',
            'ingredients' => ['Aloe Vera', 'Niacinamide', 'Rose Water'],
            'reviews' => [['Pooja T.', 5, 'Fresh look all day.'], ['Neha G.', 4, 'Keeps my makeup intact in humidity.']]],

        // ---------------- SKINCARE ----------------
        ['name' => 'Honey Beauty Daily Facial Cleanser', 'slug' => 'daily-facial-cleanser', 'category' => 'skincare', 'price' => 550, 'mrp' => 650, 'rating' => 4.5, 'image' => 'cleanser.jpg',
            'short_desc' => 'A pH-balanced daily wash that leaves skin soft, not stripped.',
            'description' => 'A gentle, sulfate-free formula that lifts away dirt and excess oil while maintaining your skin barrier, leaving skin soft and comfortable after every wash.',
            'ingredients' => ['Glycerin', 'Chamomile Extract', 'Panthenol'],
            'reviews' => [['Isha K.', 4, 'Very gentle, no tightness after.'], ['Ritu B.', 5, 'Perfect daily cleanser.']]],
        ['name' => 'Honey Beauty Nourishing Cleansing Oil', 'slug' => 'nourishing-cleansing-oil', 'category' => 'skincare', 'price' => 750, 'mrp' => 899, 'rating' => 4.6, 'image' => 'cleansing-oil.jpg',
            'short_desc' => 'Melts away heavy makeup and sunscreen in one step.',
            'description' => 'A silky oil cleanser that dissolves waterproof makeup, SPF, and daily grime without stripping moisture, rinsing clean without any greasy residue.',
            'ingredients' => ['Jojoba Oil', 'Vitamin E', 'Sweet Almond Oil'],
            'reviews' => [['Riya R.', 5, 'Works fast on stubborn makeup.'], ['Charu T.', 4, 'Skin feels soft after, not oily.']]],
        ['name' => 'Honey Beauty Gentle Cleanser for Sensitive Skin', 'slug' => 'gentle-cleanser-sensitive', 'category' => 'skincare', 'price' => 600, 'mrp' => 720, 'rating' => 4.4, 'image' => 'gentle-cleanser.jpg',
            'short_desc' => 'Mild, fragrance-free formula for reactive skin.',
            'description' => 'Formulated for sensitive and reactive skin types, this fragrance-free cleanser soothes while it cleanses, ideal for daily use even on irritated skin.',
            'ingredients' => ['Chamomile', 'Glycerin', 'Oat Extract'],
            'reviews' => [['Sara K.', 4, 'Gentle, no irritation at all.'], ['Manasi V.', 5, 'Perfect for my sensitive skin.']]],
        ['name' => 'Honey Beauty Tea Tree Face Wash', 'slug' => 'tea-tree-face-wash', 'category' => 'skincare', 'price' => 450, 'mrp' => 550, 'rating' => 4.6, 'image' => 'face-wash.jpg',
            'short_desc' => 'Anti-acne wash that keeps pores clear.',
            'description' => 'A foaming face wash powered by tea tree oil and salicylic acid to clear pores, control breakouts, and leave skin feeling refreshingly clean.',
            'ingredients' => ['Tea Tree Oil', 'Salicylic Acid', 'Aloe Vera'],
            'reviews' => [['Ananya V.', 4, 'Great for occasional breakouts.'], ['Fatima S.', 5, 'Cleared my acne in 2 weeks.']]],
        ['name' => 'Honey Beauty Walnut Face Scrub', 'slug' => 'walnut-face-scrub', 'category' => 'skincare', 'price' => 400, 'mrp' => 499, 'rating' => 4.5, 'image' => 'face-scrub.jpg',
            'short_desc' => 'Natural exfoliator that buffs away dullness.',
            'description' => 'Fine crushed walnut shells gently exfoliate dead skin cells and reveal brighter, smoother skin underneath, without over-scrubbing.',
            'ingredients' => ['Walnut Shell Powder', 'Vitamin E', 'Glycerin'],
            'reviews' => [['Mitali P.', 4, 'Super smooth skin after use.'], ['Prerna D.', 5, 'Not too harsh, love it.']]],
        ['name' => 'Honey Beauty Vitamin C Face Serum', 'slug' => 'vitamin-c-face-serum', 'category' => 'skincare', 'price' => 899, 'mrp' => 1199, 'rating' => 4.9, 'image' => 'face-serum.jpg',
            'short_desc' => 'Brightening serum that fades spots and boosts radiance.',
            'description' => 'An advanced brightening serum with 15% pure Vitamin C that visibly reduces dark spots and pigmentation while delivering a lasting radiant glow.',
            'ingredients' => ['15% Pure Vitamin C', 'Hyaluronic Acid', 'Ferulic Acid'],
            'reviews' => [['Simran V.', 5, 'Glowing skin in just 2 weeks!'], ['Alia F.', 5, 'Dark spots visibly faded.']]],
        ['name' => 'Honey Beauty Balancing Face Toner', 'slug' => 'balancing-face-toner', 'category' => 'skincare', 'price' => 550, 'mrp' => 650, 'rating' => 4.6, 'image' => 'face-toner.jpg',
            'short_desc' => 'Alcohol-free toner that preps skin for serums.',
            'description' => 'A soothing, alcohol-free toner that rebalances skin pH after cleansing and preps skin to better absorb the serums and moisturizers that follow.',
            'ingredients' => ['Rose Water', 'Aloe Vera', 'Witch Hazel'],
            'reviews' => [['Mahi R.', 4, 'Soothing, no stinging.'], ['Gauri N.', 5, 'Skin feels fresh instantly.']]],
        ['name' => 'Honey Beauty Hydrating Toner Mist', 'slug' => 'hydrating-toner-mist', 'category' => 'skincare', 'price' => 599, 'mrp' => 699, 'rating' => 4.7, 'image' => 'toner-mist.jpg',
            'short_desc' => 'A refreshing mist for an instant hydration boost anytime.',
            'description' => 'A fine facial mist packed with humectants for an instant burst of hydration, perfect for setting makeup or refreshing skin midday.',
            'ingredients' => ['Hyaluronic Acid', 'Rose Water', 'Glycerin'],
            'reviews' => [['Ekta S.', 5, 'Love spritzing this at my desk.'], ['Naina J.', 4, 'Very refreshing and light.']]],
        ['name' => 'Honey Beauty SPF Day Cream', 'slug' => 'spf-day-cream', 'category' => 'skincare', 'price' => 850, 'mrp' => 999, 'rating' => 4.7, 'image' => 'day-cream.jpg',
            'short_desc' => 'Lightweight daily moisturizer with built-in sun protection.',
            'description' => 'A non-greasy daily moisturizer enriched with SPF that hydrates skin while shielding it from daily sun exposure, perfect under makeup.',
            'ingredients' => ['Shea Butter', 'SPF 20', 'Niacinamide'],
            'reviews' => [['Kajal K.', 5, 'Non-greasy, perfect under makeup.'], ['Sunita M.', 4, 'Good daily protection.']]],
        ['name' => 'Honey Beauty Overnight Repair Night Cream', 'slug' => 'overnight-repair-night-cream', 'category' => 'skincare', 'price' => 950, 'mrp' => 1150, 'rating' => 4.8, 'image' => 'night-cream.jpg',
            'short_desc' => 'Rich, repairing cream that works while you sleep.',
            'description' => 'A deeply nourishing night cream with retinol and hyaluronic acid that repairs and replenishes skin overnight for a refreshed morning glow.',
            'ingredients' => ['Retinol', 'Hyaluronic Acid', 'Shea Butter'],
            'reviews' => [['Hina S.', 5, 'Wake up with soft, plump skin.'], ['Rekha D.', 5, 'Visible difference in a week.']]],
        ['name' => 'Honey Beauty Cooling Gel Moisturizer', 'slug' => 'cooling-gel-moisturizer', 'category' => 'skincare', 'price' => 650, 'mrp' => 780, 'rating' => 4.7, 'image' => 'gel-moisturizer.jpg',
            'short_desc' => 'Lightweight, oil-free hydration for all skin types.',
            'description' => 'A water-based gel moisturizer that absorbs instantly, delivering a cooling burst of hydration without any heavy or greasy after-feel.',
            'ingredients' => ['Aloe Vera', 'Vitamin E', 'Cucumber Extract'],
            'reviews' => [['Avi S.', 5, 'Perfect for oily skin.'], ['Tara P.', 4, 'Light and refreshing.']]],
        ['name' => 'Honey Beauty Purifying Clay Mask', 'slug' => 'purifying-clay-mask', 'category' => 'skincare', 'price' => 650, 'mrp' => 799, 'rating' => 4.8, 'image' => 'clay-mask.jpg',
            'short_desc' => 'Deep-cleans pores and mattifies in 15 minutes.',
            'description' => 'A mineral-rich clay mask that draws out impurities and excess oil, visibly minimizing pores and leaving skin smooth and mattified.',
            'ingredients' => ['Kaolin Clay', 'Aloe Vera', 'Bentonite'],
            'reviews' => [['Megha S.', 5, 'Best mask for oily skin.'], ['Preeti L.', 5, 'Pores look so much smaller.']]],
        ['name' => 'Honey Beauty Herbal Face Pack', 'slug' => 'herbal-face-pack', 'category' => 'skincare', 'price' => 450, 'mrp' => 550, 'rating' => 4.5, 'image' => 'face-pack.jpg',
            'short_desc' => 'A refreshing herbal pack for tired, dull skin.',
            'description' => 'A traditional multani-mitti based pack blended with rose water to refresh dull skin and leave it visibly brighter after just one use.',
            'ingredients' => ['Multani Mitti', 'Rose Water', 'Sandalwood'],
            'reviews' => [['Hina M.', 4, 'Refreshing, skin feels clean.'], ['Jyoti R.', 4, 'Classic and effective.']]],
        ['name' => 'Honey Beauty Hydrating Sheet Mask', 'slug' => 'hydrating-sheet-mask', 'category' => 'skincare', 'price' => 299, 'mrp' => 349, 'rating' => 4.8, 'image' => 'sheet-mask.jpg',
            'short_desc' => 'A 15-minute hydration boost for tired skin.',
            'description' => 'Soaked in a serum-rich essence, this sheet mask delivers an instant hydration boost, leaving skin plump, dewy, and refreshed.',
            'ingredients' => ['Hyaluronic Acid', 'Peptides', 'Aloe Vera'],
            'reviews' => [['Sia K.', 5, 'Refreshing before events.'], ['Roshni T.', 5, 'Skin glows instantly.']]],
        ['name' => 'Honey Beauty Overnight Lip Mask', 'slug' => 'overnight-lip-mask', 'category' => 'skincare', 'price' => 499, 'mrp' => 599, 'rating' => 4.7, 'image' => 'lip-mask.jpg',
            'short_desc' => 'Wake up to soft, plump, hydrated lips.',
            'description' => 'A rich, balm-like mask that works overnight to deeply hydrate and repair dry, chapped lips, leaving them soft and smooth by morning.',
            'ingredients' => ['Shea Butter', 'Vitamin E', 'Coconut Oil'],
            'reviews' => [['Aditi N.', 5, 'Lips are so soft in the morning.'], ['Vidya S.', 4, 'Great texture, not sticky.']]],
        ['name' => 'Honey Beauty Matte Sunscreen SPF 50', 'slug' => 'matte-sunscreen-spf-50', 'category' => 'skincare', 'price' => 650, 'mrp' => 799, 'rating' => 4.7, 'image' => 'sunscreen.jpg',
            'short_desc' => 'Broad-spectrum protection with zero white cast.',
            'description' => 'A lightweight, non-sticky sunscreen offering broad-spectrum SPF 50 protection with a matte finish and no white cast on any skin tone.',
            'ingredients' => ['Zinc Oxide', 'Cucumber Extract', 'Vitamin E'],
            'reviews' => [['Raj K.', 5, 'No white cast at all.'], ['Komal B.', 5, 'Perfect matte finish.']]],
        ['name' => 'Honey Beauty Hydrating Micellar Water', 'slug' => 'hydrating-micellar-water', 'category' => 'skincare', 'price' => 550, 'mrp' => 650, 'rating' => 4.8, 'image' => 'micellar-water.jpg',
            'short_desc' => 'No-rinse cleanser that removes makeup effortlessly.',
            'description' => 'A gentle, no-rinse micellar water that lifts away makeup, dirt, and oil with a swipe of cotton, leaving skin feeling clean and hydrated.',
            'ingredients' => ['Micelles', 'Glycerin', 'Purified Water'],
            'reviews' => [['Kriti S.', 5, 'Very clean feel, no residue.'], ['Sanya D.', 4, 'Removes waterproof mascara easily.']]],
        ['name' => 'Honey Beauty Brightening Under Eye Cream', 'slug' => 'brightening-under-eye-cream', 'category' => 'skincare', 'price' => 650, 'mrp' => 799, 'rating' => 4.7, 'image' => 'under-eye-cream.jpg',
            'short_desc' => 'Reduces the look of dark circles and puffiness.',
            'description' => 'A caffeine-infused eye cream that visibly reduces dark circles and puffiness, leaving the delicate eye area looking brighter and refreshed.',
            'ingredients' => ['Caffeine', 'Vitamin K', 'Hyaluronic Acid'],
            'reviews' => [['Mina S.', 5, 'Visible results in days.'], ['Payal R.', 4, 'Puffiness reduced noticeably.']]],
        ['name' => 'Honey Beauty 24H Body Lotion', 'slug' => '24h-body-lotion', 'category' => 'skincare', 'price' => 550, 'mrp' => 650, 'rating' => 4.6, 'image' => 'body-lotion.jpg',
            'short_desc' => 'Silky, fast-absorbing lotion for all-day soft skin.',
            'description' => 'A rich yet fast-absorbing body lotion that locks in moisture for up to 24 hours, leaving skin soft, smooth, and never greasy.',
            'ingredients' => ['Shea Butter', 'Almond Oil', 'Vitamin E'],
            'reviews' => [['Rahul P.', 4, 'Soft skin all day.'], ['Deepika A.', 5, 'Absorbs fast, smells great.']]],
        ['name' => 'Honey Beauty Nourishing Lip Balm', 'slug' => 'nourishing-lip-balm', 'category' => 'skincare', 'price' => 299, 'mrp' => 349, 'rating' => 4.6, 'image' => 'lip-balm.jpg',
            'short_desc' => 'Everyday hydration for soft, healthy lips.',
            'description' => 'A gentle, nourishing balm that melts on contact to relieve dryness and keep lips soft and healthy throughout the day.',
            'ingredients' => ['Shea Butter', 'Beeswax', 'Vitamin E'],
            'reviews' => [['Anita G.', 5, 'Keeps lips soft all winter.'], ['Bhoomi K.', 4, 'Great everyday balm.']]],
        ['name' => 'Honey Beauty Tinted Lip Balm Stick', 'slug' => 'tinted-lip-balm-stick', 'category' => 'skincare', 'price' => 349, 'mrp' => 429, 'rating' => 4.5, 'image' => 'lip-balm-stick.jpg',
            'short_desc' => 'A sheer wash of color with nourishing balm care.',
            'description' => 'Part balm, part color, this tinted stick hydrates lips while leaving behind a sheer, buildable flush that looks effortlessly natural.',
            'ingredients' => ['Shea Butter', 'Jojoba Oil', 'Fruit Extracts'],
            'reviews' => [['Ishita V.', 5, 'Perfect no-makeup makeup look.'], ['Radhika S.', 4, 'Nice natural tint.']]],

        // ---------------- TOOLS ----------------
        ['name' => 'Honey Beauty Precision Blending Brush', 'slug' => 'precision-blending-brush', 'category' => 'tools', 'price' => 450, 'mrp' => 550, 'rating' => 4.6, 'image' => 'brush.jpg',
            'short_desc' => 'Soft synthetic bristles for seamless blending.',
            'description' => 'A densely packed synthetic brush designed for effortless blending of powders, creams, and liquids without absorbing excess product.',
            'ingredients' => ['Synthetic Fibers', 'Wood Handle'],
            'reviews' => [['Heena R.', 5, 'So soft, blends perfectly.'], ['Chandni M.', 5, 'Great quality for the price.']]],
        ['name' => 'Honey Beauty Pro Brush Set (12pc)', 'slug' => 'pro-brush-set-12pc', 'category' => 'tools', 'price' => 1499, 'mrp' => 1899, 'rating' => 4.9, 'image' => 'brush-set.jpg',
            'short_desc' => 'A complete 12-piece professional brush collection.',
            'description' => 'Everything you need for a full face, from foundation to fine detail work, packed in a travel-friendly leather pouch with high-grade synthetic bristles.',
            'ingredients' => ['High-Grade Fibers', 'Leather Pouch'],
            'reviews' => [['Kiran G.', 5, 'Great kit, worth every rupee.'], ['Lavanya T.', 5, 'Professional quality at home.']]],
        ['name' => 'Honey Beauty Blender Sponge', 'slug' => 'blender-sponge', 'category' => 'tools', 'price' => 299, 'mrp' => 375, 'rating' => 4.8, 'image' => 'beauty-blender.jpg',
            'short_desc' => 'Latex-free sponge for a flawless, airbrushed base.',
            'description' => 'A latex-free, dual-density sponge that expands with water for streak-free foundation and concealer application every time.',
            'ingredients' => ['Hydrophilic Foam'],
            'reviews' => [['Sapna K.', 5, 'Best quality, doubles in size.'], ['Urvashi N.', 5, 'Flawless application every time.']]],
        ['name' => 'Honey Beauty Precision Eyelash Curler', 'slug' => 'precision-eyelash-curler', 'category' => 'tools', 'price' => 350, 'mrp' => 429, 'rating' => 4.5, 'image' => 'eyelash-curler.jpg',
            'short_desc' => 'Ergonomic curler for a lasting, natural lift.',
            'description' => 'A stainless steel curler with a silicone pad that lifts lashes with a comfortable, even curl that lasts all day without pinching.',
            'ingredients' => ['Stainless Steel', 'Silicone Pads'],
            'reviews' => [['Riya P.', 4, 'Perfect lift, no pinching.'], ['Sneha V.', 5, 'Curl lasts all day.']]],
        ['name' => 'Honey Beauty Express Makeup Remover', 'slug' => 'express-makeup-remover', 'category' => 'tools', 'price' => 450, 'mrp' => 550, 'rating' => 4.6, 'image' => 'makeup-remover.jpg',
            'short_desc' => 'Fast-acting formula that melts away every trace of makeup.',
            'description' => 'An oil-infused, fast-action remover that breaks down even stubborn waterproof makeup in seconds, leaving skin clean and never stripped.',
            'ingredients' => ['Micelles', 'Argan Oil'],
            'reviews' => [['Om K.', 5, 'Fast, removes everything.'], ['Vaishnavi R.', 4, 'Gentle on the eyes.']]],
    ];
}
