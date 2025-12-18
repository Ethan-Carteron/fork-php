SELECT *
FROM expense
INNER JOIN wallet
    ON expense.wallet_id = wallet.id
    AND wallet.is_deleted = false
    AND wallet.id = :walletId
WHERE expense.is_deleted = false
ORDER BY expense.created_date DESC
LIMIT :limit
OFFSET(:page - 1) * :limit;
