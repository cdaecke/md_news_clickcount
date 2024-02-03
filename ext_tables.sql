CREATE TABLE tx_news_domain_model_news
(
    md_news_clickcount_count int(11) DEFAULT '0' NOT NULL
);


#
# Table structure for table 'md_news_clickcount_log'
#
CREATE TABLE tx_mdnewsclickcount_log
(
    ip       tinytext,
    log_date date    DEFAULT NULL,
    news     int(11) DEFAULT '0' NOT NULL,
);
