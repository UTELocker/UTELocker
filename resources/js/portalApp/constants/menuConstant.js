export default  {
    booking : {
        title: 'Đặt Locker',
        path: '/portal/booking',
    },
    portal : {
        title: 'UTELocker Portal',
        children: [
            {
                title: 'Trang chủ',
                path: '/portal',
            },
            {
                title: 'Địa điểm',
                path: '/portal/locations',
            },
            {
                title: 'Lịch sử đặt',
                path: '/portal/histories',
            },
        ]
    },
    wallet : {
        title: 'Ví điện tử UTEPay',
        children: [
            {
                title: 'Ví của tôi',
                path: '/wallet',
            },
            {
                title: 'Lịch sử giao dịch',
                path: '/wallet/transactions',
            }
        ]
    },
}
