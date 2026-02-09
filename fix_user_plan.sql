-- Fix User Plan Assignment
-- This updates the user's plan_id to match their active subscription

UPDATE users 
SET plan_id = 3,  -- Pro plan ID
    currency = 'INR',
    billing_cycle_start = NOW(),
    messages_this_month = 0
WHERE email = 'jay158484@gmail.com';

-- Verify the update
SELECT 
    u.id,
    u.email,
    u.plan_id,
    p.name as plan_name,
    u.currency,
    (SELECT COUNT(*) FROM subscriptions WHERE user_id = u.id AND status = 'active') as active_subscriptions
FROM users u
LEFT JOIN plans p ON u.plan_id = p.id
WHERE u.email = 'jay158484@gmail.com';
