/bin/grep -i $1 /etc/config/wireless && exit 1
/sbin/uci add_list wireless.@wifi-iface[0].maclist=$1
/sbin/uci commit wireless
/sbin/wifi
