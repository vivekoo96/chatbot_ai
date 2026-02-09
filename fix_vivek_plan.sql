-- Fix vivek's plan to match his active Pro subscription
UPDATE users 
SET plan_id = 3,  -- Pro plan ID (based on subscription data)
    currency = 'INR',
    billing_cycle_start = NOW(),
    messages_this_month = 0
WHERE email = 'jay158484@gmail.com';

-- Verify the update
SELECT 
    u.id,
    u.email,
    u.plan_id,
    p.name as current_plan,
    u.currency,
    u.messages_this_month,
    (SELECT COUNT(*) FROM subscriptions WHERE user_id = u.id AND status = 'active') as active_subs
FROM users u
LEFT JOIN plans p ON u.plan_id = p.id
WHERE u.email = 'jay158484@gmail.com';
