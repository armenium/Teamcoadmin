##############################################################
#	Скрипт для автоматического резервного копирования
#	базы данных.
#	Версия 1
#
#	Автор:	Антон Аксенов
#	URL:	anthonyaxenov.ru
#	Email:	anthonyaxenov@gmail.com
#
#	Скрипт делает дамп базы данных, архивирует и располагает
#	в указанной папке. Можно подключить облачное хранилище
#	которое подключается по WebDAV (рекомендуется davfs2).
#	Можно задать максимальный размер папки с бекапами.
#	При превышении этого размера из папки будут удаляться
#	более старые файлы.
#
#	Подробности о скрипте, подготовка к работе:
#	https://anthonyaxenov.blogspot.ru/2017/05/cron-1.html
#
##############################################################
#!/bin/bash

# Данные для работы с БД
DBHOST=localhost # Адрес БД
DBUSER=jersey_builder # Имя пользователя базы данных
DBPASSWD=CLujiRUCMaljdZFP8Grf # Пароль от базы данных
DBNAME=jersey_builder # Имя базы данных для резервного копирования
DBCHARSET=utf8 # Кодировка базы данных (utf8)

# Даты
DATE=`date +%F` # Префикс для структурирования бекапов (формат: 2017-01-01)
DATETIME=`date +%F-%H-%M-%S` # Полная текущая дата и время (формат: 2017-01-01-12-23-34)

# Локальное хранилище
LOCALDIR=/var/www/teamcoadmin.com/public_html/rahimnew/db-dumps # Полный путь к каталогу, где будут храниться резервные копии
LOCALPATH=$LOCALDIR/$DATE # Полный путь к папке за сегодня
LOCALFILE=$LOCALPATH/$DBNAME-$DATETIME.sql # Полный путь к файлу дампа
LOCALFILEGZ=$LOCALFILE.gz # Полный путь к архиву дампа
# Путь к бекапу будет выглядеть так:
# /root/db_backup/2017-01-01/mybigdatabase-2017-01-01-12-23-34.sql.gz

# Облачное хранилище
CLOUDUSE=0 # Копировать ли в облако? Закомментировать строку, если не надо
CLOUDMNT=/mnt/yadisk # Точка монтирования облака относительно корня
CLOUDDIR=db_backup # Папка в облаке, куда будут лететь файлы (внутри папки CLOUDMNT, т.е. без / в начале)
CLOUDPATH=$CLOUDMNT/$CLOUDDIR/$DATE # Полный путь к папке текущей даты в облаке относительно корня
CLOUDFILE=$CLOUDPATH/$DBNAME-$DATETIME.sql # Полный путь к файлу дампа в облаке
CLOUDFILEGZ=$CLOUDFILE.gz # Полный путь к архиву в облаке

# Путь к бекапу на примонтированном хранилище будет выглядеть так:
# /mnt/yadisk/db_backup/2017-01-01/mybigdatabase-2017-01-01-12-23-34.sql.gz

# Начало процесса
echo "[--------------------------------[`date +%F-%H-%M-%S`]--------------------------------]"
echo "[`date +%F-%H-%M-%S`] Starting backup"
if ! [[ -d $LOCALPATH ]]; then # Если нет папки за сегодня
	mkdir $LOCALPATH 2> /dev/null # создаём её, ошибки игнорируем
fi
echo "[`date +%F-%H-%M-%S`] Generate a database dump: '$DBNAME'..."
mysqldump --user=$DBUSER --host=$DBHOST --password=$DBPASSWD -q --default-character-set=$DBCHARSET $DBNAME > $LOCALFILE
if [[ $? -gt 0 ]]; then
	# если дамп сделать не удалось (код завершения предыдущей команды больше нуля) - прерываем весь скрипт
	echo "[`date +%F-%H-%M-%S`] Dumping failed! Script aborted."
	exit 1
else # иначе - упаковываем его
	echo "[`date +%F-%H-%M-%S`] Dumping successfull! Packing in GZIP..."
	gzip $LOCALFILE # Упаковка
	if [[ $? -ne 0 ]]; then # Если не удалась
		echo "[`date +%F-%H-%M-%S`] GZipping failed! SQL-file will be uploaded."
		GZIP_FAILED=1 # Создаём флажок, что упаковка сорвалась
	else
		echo "[`date +%F-%H-%M-%S`] Result file: $LOCALFILEGZ"
	fi
	if [[ $CLOUDUSE -eq 1 ]]; then # Если задано копирование в облако - делаем всякое такое
		mount | grep "$CLOUDMNT" > /dev/null # Проверяем примонтировано ли уже у нас облако  (вывод не важен)
		if [[ $? -ne 0 ]]; then # Если нет
			mount $CLOUDMNT # значит монтируем
		fi
		if [[ $? -eq 0 ]]; then # если монтирование успешно - копируем туда файл
			echo "[`date +%F-%H-%M-%S`] Cloud: successfully mounted at $CLOUDMNT"
			echo "[`date +%F-%H-%M-%S`] Cloud: copying started => $CLOUDFILEGZ"
			if ! [[ -d $CLOUDPATH ]]; then # Если в облаке нет папки за сегодня
				mkdir $CLOUDPATH 2> /dev/null # создаём её, ошибки игнорируем
			fi
			if [[ -f $LOCALFILEGZ && GZIP_FAILED -ne 1 ]]; then # Если у нас архивирование выше не сорвалось
				cp -R $LOCALFILEGZ $CLOUDFILEGZ # Копируем архив
			else
				cp -R $LOCALFILE $CLOUDFILE # Иначе - копируем большой тяжёлый дамп
			fi
			if [[ $? -gt 0 ]]; then # Если не скопировался - просто сообщаем
				echo "[`date +%F-%H-%M-%S`] Cloud: copy failed."
			else # Если скопировался - сообщаем и размонтируем
				echo "[`date +%F-%H-%M-%S`] Cloud: file successfully uploaded!"
				umount $CLOUDMNT # Размонтирование облака
				if [[ $? -gt 0 ]]; then # Сообщаем результат размонтирования (если необходимо)
					echo "[`date +%F-%H-%M-%S`] Cloud: umount - failed!"
				fi # Конец проверки успешного РАЗмонтирования
			fi  # Конец проверки успешного копирования
		else # если монтирование НЕуспешно - сообщаем
			echo "[`date +%F-%H-%M-%S`] Cloud: failed to mount cloud at $CLOUDMNT"
		fi # Конец проверки успешного монтирования
	fi # Конец проверки необходимости выгрузки в облако
fi # Конец проверки успешного выполнения mysqldump
echo "[`date +%F-%H-%M-%S`] Stat datadir space (USED): `du -h $LOCALPATH | tail -n1`" # вывод размера папки с бэкапами за текущий день
echo "[`date +%F-%H-%M-%S`] Free HDD space: `df -h /home|tail -n1|awk '{print $4}'`" # вывод свободного места на локальном диске
echo "[`date +%F-%H-%M-%S`] All operations completed!"
exit 0 # Успешное завершение скрипта
