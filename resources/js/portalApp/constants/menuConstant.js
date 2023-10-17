export default  {
    booking : {
        title: 'New Booking',
        path: '/portal/booking',
    },
    portal : {
        title: 'Portal',
        children: [
            {
                title: 'Overview',
                path: '/portal',
            },
            {
                title: 'Locations',
                path: '/portal/locations',
            },
            {
                title: 'Historical Bookings',
                path: '/portal/histories',
            },
        ]
    },
    wallet : {
        title: 'Wallet',
        children: [
            {
                title: 'Overview',
                path: '/wallet',
            },
            {
                title: 'Transactions',
                path: '/wallet/transactions',
            }
        ]
    },
}
