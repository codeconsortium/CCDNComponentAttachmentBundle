Upgrading CCDNForum ForumBundle to 1.1.2
=========================================

Run this SQL to upgrade.

```sql
set foreign_key_checks=0;

ALTER TABLE CC_Component_Attachment
	DROP FOREIGN KEY FK_3E30D3EE6DD3F56D;
	DROP INDEX IDX_3E30D3EE6DD3F56D ON CC_Component_Attachment;

ALTER TABLE CC_Component_Attachment
	CHANGE owned_by_user_id fk_owned_by_user_id INT DEFAULT NULL,
	CHANGE attachment_original file_name_original VARCHAR(255) DEFAULT NULL,
	CHANGE attachment_hashed file_name_hashed VARCHAR(255) DEFAULT NULL,
	CHANGE file_extension file_extension VARCHAR(10) DEFAULT NULL,
	CHANGE file_size file_size TINYTEXT DEFAULT NULL;
		
ALTER TABLE CC_Component_Attachment 
	ADD CONSTRAINT FK_3E30D3EE3BB9921A FOREIGN KEY (fk_owned_by_user_id) REFERENCES fos_user(id) ON DELETE SET NULL;

CREATE INDEX IDX_3E30D3EE3BB9921A ON CC_Component_Attachment (fk_owned_by_user_id);

set foreign_key_checks=1;
```

- [Return back to the docs index](index.md).
