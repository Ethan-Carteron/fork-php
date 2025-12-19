SELECT *
FROM "user";

-- ALICE = 21

UPDATE xuser_wallet
SET is_deleted = false
WHERE target_user_id = 21;

SELECT *
FROM xuser_wallet;
