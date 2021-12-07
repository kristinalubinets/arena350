TRUNCATE events;
INSERT INTO events (name, description, date)
VALUES ('Football Game: Barcelona vs Manchester United', 'The Champions League final between Manchester United and Barcelona is now just days away, and fans are eagerly anticipating a great final between the two, but most fans are still divided as to just who they think will come out on top.

People look to the ''09 final as to just how United was brushed aside and assume the same will happen again, but there have been much more games between the two clubs than that one, and ones which have had huge significance as well.

There’s quite a history to this fixture that has never let us down in terms of entertainment, so I’ve decided to look back at all the competitive fixtures between the two and see just who has come out on top the most.', '2021-12-14 20:00:00');
DELETE FROM user_tickets;
DELETE FROM tickets;
INSERT INTO tickets(event_id, seat, price)
VALUES (1, 'A1', 200.00),
       (1, 'A2', 250.00),
       (1, 'B1', 150.00),
       (2, 'C1', 50.50),
       (2, 'C2', 75.00),
       (2, 'C3', 88.00),
       (3, 'A2', 15.00),
       (3, 'A4', 22.00),
       (4, 'Z1', 25.00),
       (5, 'D4', 54.00);

