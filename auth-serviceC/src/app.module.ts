import { Module } from '@nestjs/common';
import {AuthController} from "./app/modules/auth/auth.controller";

@Module({
  imports: [],
  controllers: [AuthController]
})
export class AppModule {}
