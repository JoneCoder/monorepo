import { Controller } from '@nestjs/common';
import { GrpcMethod } from '@nestjs/microservices';

@Controller()
export class AuthController {

    @GrpcMethod('AuthService', 'AuthCheck')
    async authCheck(data: { token: string }) {

        console.log('Token received:', data.token);

        // Example validation logic
        if (data.token === 'valid-token') {
            return {
                success: true,
                message: 'Token is valid',
                user: {
                    id: '1',
                    name: 'Shariful',
                    email: 'test@example.com',
                    verified_at: new Date().toISOString(),
                },
            };
        }

        return {
            success: false,
            message: 'Invalid token',
            user: null,
        };
    }
}