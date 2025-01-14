create table rides
(
    id        varchar(255)     not null primary key,
    -- rider_id  varchar(255)     not null,
    -- driver_id varchar(255),
    departure VARCHAR(255)     not null,
    arrival   VARCHAR(255)     not null,
    -- distance  double precision not null,
    uber_x    boolean          not null,
    price     double precision not null
);
